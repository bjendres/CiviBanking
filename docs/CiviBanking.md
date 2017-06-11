# CiviBanking-Documentation
The CiviBanking extension implements handling of bank accounts for contacts, as well as handling of bank files (and individual bank payements extracted from the files). Bank files can be imported, payments matched to CiviCRM entities, and the resulting data exported. Specific handlers for all of these actions are provided through plugins, some of which are shipped with the core banking extension, while some more complex ones are provided in separate extensions.

## Project Status

The project is stable, and has been in productive use by various organisations since Febuary 2014. However, it's still lacking (the funding for) an easy-to use configuration user interface. But once it's set up and configured correctly, it's very reliable. We encourage you to contact us, if you're interested in an implementation with your organisation.

You'll find all the information you need here:

 * Maintainer: Björn Endres (endres (at) systopia.de)
 * Status: development stable
 * Releases: https://github.com/Project60/CiviBanking/releases
 * Repository: https://github.com/Project60/CiviBanking/
 * Planned releases: https://github.com/Project60/CiviBanking/milestones
 * Bugs and issues: https://github.com/Project60/CiviBanking/issues

## Extensibility
The framework defines a certain workflow and basic UI, everything else is performed by various plugins. The system comes with a set of plugins, but it's easy to create (and share!) new ones, to accommodate your organisation's needs. The four basic plugin classes are:

Plugin Type | What it does | For example
------------ | ------------- | ------------
Import | Allows the user (or a push service) to __import__ proprietary data into the internal __bank transaction__ data set.  | There will be import plugins for most standard national banking formats, and likely also for importing transactions (or pushing transactions) from accounting software.
Analyse/Match | __Detects__ a specific pattern of correlation between financial transactions (typically payments) on one hand and existing or newly created Civi objects (such as contributions, memberships, contacts, ...) and has the ability to __execute__ the changes necessary to implement the match. Matchers will either be full __automatic__ (no user interference required), full __manual__ (only a suggestion is made to the end user) or __assisted__ (suggestions are made, and the user selects one of the options to execute). | Examples of matchers are: detect payment of a pending membership renewal; detect a new membership and contact based on a specific amount; create contributions for new payments to be considered single contribution
PostProcess | __After the reconciliation__ of a bank transaction with a contribution, a post processor could update a contact's address or bank information,add tags or trigger actions. This is still being developed.	 | A post processor might e.g. detect a difference between a contact's address on the bank statement and your recorded address. Therefore it creates an activity for someone to look into this.
Export | CiviBanking not only keeps all the bank statements and the individual transactions, but also the information how, when, why and by whom it was reconciled. To __make this data accessible__ to you and or peripheral systems, these plugins can format and export it.	 | You could maybe develop an exporter to give you a monthly report spreadsheet of all the bookings in your system.

## System Sketches

<a href='../img/System-Sketches.png'><img alt='CiviBanking System Sketches' src='../img/System-Sketches.png'/></a>

