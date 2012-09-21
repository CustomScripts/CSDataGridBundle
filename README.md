DataGrid Bundle
===============

This is a bundle for Symfony2 to create a grid to show records in a table

Requirements
------------

This bundle currently only works with Symfony 2.1.
Using the bundle in previous versions of Symfony may cause unwanted results.

Features
--------

The following features are planned for this bundle. 

**Note:** Some of the features are currently being worked on, while some have not yet been implemented.

* Custom Grid class to easilly manage all aspects of your grid
* Multiple Data sources including Doctrine Query Builder, Array etc
* Pagination
* Custom Columns
* Custom Row Data
* Filters
* Actions
* Search

Installation
------------

To install this bundle in your Symfony2 application, add the following in your composer.json file:

    "require": {
        ....
        "customscripts/datagrid-bundle" : "dev-master"

Update your dependencies with:

    php composer.phar update
    

then register the bundle in your AppKernel.php:

    $bundles = array(
        ...
        new CS\DataGridBundle\CSDataGridBundle(),
    );
    

Creating your first Grid
----------------------

Coming Soon!

This section will show you how to create your grid and configure it.


Contributing
------------

If you wish to contribute to this bundle, fork it, make your changes and send a pull request.