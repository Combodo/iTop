.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

DataTable
=========

Description
-----------

This block is used to display data in a tabular way.
Data can come from the database (*Dynamic DataTable*) or from a static array (*Static DataTable*).

Examples
--------

Dynamic DataTable

.. image:: Datatable.png

Static DataTable

.. image:: DatatableStatic.png

Twig Tag
--------

:Tag: **UIDataTable**

:Syntax:

::

    {% UIDataTable Type {Parameters} %}
        Content Goes Here
    {% EndUIDataTable %}

:Type:

- **ForResult**

Create a table from search results. The data to display are given using a *DBObjectSet*.


----

- **ForObject**

Create a table from search results. The data to display are given using a *DBObjectSet*.

----

- **ForRendering**

Create a table from search results. The data to display are given using a *DBObjectSet*.


----

- **ForRenderingObject**

Create a table from search results. The data to display are given using a *DBObjectSet*.


----

- **ForStaticData**

Create a table from static data.

:Parameters:

+-------------------+--------+-----------+----------------------------------+
| *sTitle*          | string | mandatory | Title of the table               |
+-------------------+--------+-----------+----------------------------------+
| *aColumns*        | array  | mandatory | Array of columns                 |
+-------------------+--------+-----------+----------------------------------+
| *aData*           | array  | optional  | Array of data                    |
+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | Id of the block                  |
+-------------------+--------+-----------+----------------------------------+
| *aExtraParams*    | array  | optional  | Array of extra parameters        |
+-------------------+--------+-----------+----------------------------------+
| *sFilter*         | string | optional  | OQL filter                       |
+-------------------+--------+-----------+----------------------------------+
| *aOptions*        | array  | optional  | Array of options                 |
+-------------------+--------+-----------+----------------------------------+


The columns (*aColumns*) have the following format:

::

    [
        'nameField1' => ['label' => labelField1, 'description' => descriptionField1],
        ...
    ]

The data (*aData*) format has to be:

::

    [
        ['nameField1' => valueField1, 'nameField2' => valueField2, ...],
        ...
    ]


----

- **ForForm**

Create a table from static data.

:Parameters:

+-------------------+--------+-----------+----------------------------------+
| *sRef*            | string | mandatory | Title of the table               |
+-------------------+--------+-----------+----------------------------------+
| *aColumns*        | array  | mandatory | Array of columns                 |
+-------------------+--------+-----------+----------------------------------+
| *aData*           | array  | optional  | Array of data                    |
+-------------------+--------+-----------+----------------------------------+
| *sFilter*         | string | optional  | OQL filter                       |
+-------------------+--------+-----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`


The columns (*aColumns*) have the following format:

::

    [
        'nameField1' => ['label' => labelField1, 'description' => descriptionField1],
        ...
    ]

The data (*aData*) format has to be:

::

    [
        ['nameField1' => valueField1, 'nameField2' => valueField2, ...],
        ...
    ]



