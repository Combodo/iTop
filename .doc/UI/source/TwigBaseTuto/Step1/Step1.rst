.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Step1:

1. Creating the structure
=========================

Create a module using the `helper to create a new extension <https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Adatamodel#creating_your_own_extension>`_ you'll get the following structure::

    my-module
    ├── assets
    │   ├── css
    │   ├── img
    │   └── js
    ├── doc
    ├── src
    │   ├── Controller
    │   ├── Helper
    │   ├── Hook
    │   ├── Model
    │   └── Service
    └── vendor

``src/Controller``

    Contains all the PHP to control the display of the different pages of the module.

``src/Service``

    Contains the PHP used to generate the data to be displayed.

Create a folder ``templates`` for the *Twig* templates used for the presentation::

    my-module
    ├── assets
    │   ├── css
    │   ├── img
    │   └── js
    ├── doc
    ├── src
    │   ├── Controller
    │   ├── Helper
    │   ├── Hook
    │   ├── Model
    │   └── Service
    ├── templates
    └── vendor


If your module is for iTop version 3.0 and above, you can put all the dictionaries into a dedicated folder ``dictionaries``::

    my-module
    ├── assets
    │   ├── css
    │   ├── img
    │   └── js
    ├── dictionaries
    ├── doc
    ├── src
    │   ├── Controller
    │   ├── Helper
    │   ├── Hook
    │   ├── Model
    │   └── Service
    ├── templates
    └── vendor



