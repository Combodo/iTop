.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _DataTable:

DataTable
=========

Class DataTable

----

.. include:: /manual/Component/DataTable/DataTableAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIDataTable**

:Syntax:

.. code-block:: twig

    {% UIDataTable Type {Parameters} %}
        Content Goes Here
    {% EndUIDataTable %}

:Type:

+---------------------------------------------------------+------------------------------+
| :ref:`ForResult <DataTableForResult>`                   | @param \WebPage $oPage       |
+---------------------------------------------------------+------------------------------+
| :ref:`ForObject <DataTableForObject>`                   | @param \WebPage $oPage       |
+---------------------------------------------------------+------------------------------+
| :ref:`ForRendering <DataTableForRendering>`             | Make a basis Panel component |
+---------------------------------------------------------+------------------------------+
| :ref:`ForRenderingObject <DataTableForRenderingObject>` | @param string $sListId       |
+---------------------------------------------------------+------------------------------+
| :ref:`ForStaticData <DataTableForStaticData>`           | No comment                   |
+---------------------------------------------------------+------------------------------+
| :ref:`ForForm <DataTableForForm>`                       | @param string $sRef          |
+---------------------------------------------------------+------------------------------+

.. _DataTableForResult:

DataTable ForResult
^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForResult {oPage:value, sListId:'value', oSet:value, aExtraParams:value} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+--------------+-------------+-----------+----------+--+
| oPage        | WebPage     | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| sListId      | string      | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| oSet         | DBObjectSet | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| aExtraParams |             | optional  | array () |  |
+--------------+-------------+-----------+----------+--+

.. _DataTableForObject:

DataTable ForObject
^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForObject {oPage:value, sListId:'value', oSet:value, aExtraParams:value} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+--------------+-------------+-----------+----------+--+
| oPage        | WebPage     | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| sListId      | string      | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| oSet         | DBObjectSet | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| aExtraParams |             | optional  | array () |  |
+--------------+-------------+-----------+----------+--+

.. _DataTableForRendering:

DataTable ForRendering
^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForRendering {sListId:'value', oSet:value, aExtraParams:value} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+--------------+-------------+-----------+----------+--+
| sListId      | string      | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| oSet         | DBObjectSet | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| aExtraParams |             | optional  | array () |  |
+--------------+-------------+-----------+----------+--+

.. _DataTableForRenderingObject:

DataTable ForRenderingObject
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForRenderingObject {sListId:'value', oSet:value, aExtraParams:value} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+--------------+-------------+-----------+----------+--+
| sListId      | string      | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| oSet         | DBObjectSet | mandatory |          |  |
+--------------+-------------+-----------+----------+--+
| aExtraParams |             | optional  | array () |  |
+--------------+-------------+-----------+----------+--+

.. _DataTableForStaticData:

DataTable ForStaticData
^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForStaticData {sTitle:'value', aColumns:{name:value, name:value}, aData:{name:value, name:value}, sId:'value', aExtraParams:{name:value, name:value}, sFilter:'value', aOptions:{name:value, name:value}} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+--------------+--------+-----------+----------+--+
| sTitle       | string | mandatory |          |  |
+--------------+--------+-----------+----------+--+
| aColumns     | array  | mandatory |          |  |
+--------------+--------+-----------+----------+--+
| aData        | array  | mandatory |          |  |
+--------------+--------+-----------+----------+--+
| sId          | string | optional  | NULL     |  |
+--------------+--------+-----------+----------+--+
| aExtraParams | array  | optional  | array () |  |
+--------------+--------+-----------+----------+--+
| sFilter      | string | optional  | ''       |  |
+--------------+--------+-----------+----------+--+
| aOptions     | array  | optional  | array () |  |
+--------------+--------+-----------+----------+--+

.. _DataTableForForm:

DataTable ForForm
^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIDataTable ForForm {sRef:'value', aColumns:{name:value, name:value}, aData:{name:value, name:value}, sFilter:'value'} %}
        Content Goes Here
    {% EndUIDataTable %}

:parameters:

+----------+--------+-----------+----------+--+
| sRef     | string | mandatory |          |  |
+----------+--------+-----------+----------+--+
| aColumns | array  | mandatory |          |  |
+----------+--------+-----------+----------+--+
| aData    | array  | optional  | array () |  |
+----------+--------+-----------+----------+--+
| sFilter  | string | optional  | ''       |  |
+----------+--------+-----------+----------+--+

DataTable common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AjaxData          | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AjaxUrl           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value'] |
+-------------------+----------+------------------------------------------------------------+
| DeferredBlocks    | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| DisplayColumns    |          |                                                            |
+-------------------+----------+------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                |
+-------------------+----------+------------------------------------------------------------+
| JSRefresh         | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| Options           |          |                                                            |
+-------------------+----------+------------------------------------------------------------+
| ResultColumns     |          |                                                            |
+-------------------+----------+------------------------------------------------------------+
| SubBlocks         | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Component/DataTable/DataTableFooter.rst
