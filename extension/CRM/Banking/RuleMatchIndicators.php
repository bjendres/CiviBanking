<?php

/*-------------------------------------------------------+
| Project 60 - CiviBanking                               |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: B. Zschiedrich (zschiedrich -at- systopia.de)  |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL v3 license. You can redistribute it and/or  |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Banking_ExtensionUtil as E;

/**
 * Shows an indicator if there is a rule match in the transaction summary.
 * This uses the hook "hook_civicrm_banking_transaction_summary".
 */
class CRM_Banking_RuleMatchIndicators
{
    /**
     * @var CRM_Banking_BAO_BankTransaction
     */
    private $transaction;

    /**
     * @var array
     */
    private $blocks;

    /**
     * @param CRM_Banking_BAO_BankTransaction $transaction
     * @param array $blocks
     */
    public function __construct($transaction, &$blocks)
    {
        $this->transaction = $transaction;
        $this->blocks = &$blocks;
    }

    public function addContactMatchIndicator()
    {
    }

    public function addIbanMatchIndicator()
    {
        $iban = $this->transaction->getDataParsed()['_IBAN']; // TODO: How will this work with other reference like for PayPal?

        if ($iban === null) {
            return;
        }

        $sql =
        "SELECT
            id
        FROM
            civicrm_bank_rules
        WHERE
            party_ba_ref = %1
        ";

        $parameters = [
            1 => [$iban, 'String'],
        ];

        $ruleDao = CRM_Core_DAO::executeQuery($sql, $parameters);

        $result = $ruleDao->fetchAll();

        if (!empty($result)) {
            // Find the position after the IBAN to safely insert the indicator:
            $position = strpos($this->blocks['ReviewDebtor'], $iban);
            $position = strpos($this->blocks['ReviewDebtor'], '</div>', $position) - 1;

            $ibanMatchIndicator = ' <a href="' .
                CRM_Utils_System::url('civicrm/a/#/banking/rules/' . $result[0]['id']) .
                '">' .
                E::ts('Banking Rule exists') .
                '</a>';

            if (count($result) > 1) {
                $ibanMatchIndicator .=
                    ' <a href="' .
                    CRM_Utils_System::url('civicrm/a/#/banking/rules') .
                    '">' .
                    E::ts('(and %1 more)', [1 => count($result)]) .
                    '</a>';
            }

            $this->blocks['ReviewDebtor'] =
                substr($this->blocks['ReviewDebtor'], 0, $position) .
                $ibanMatchIndicator .
                substr($this->blocks['ReviewDebtor'], $position);
        }
    }
}
