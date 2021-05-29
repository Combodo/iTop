.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _UIContentBlock:

UIContentBlock
==============

Class UIContentBlock
Base block containing sub-blocks

----

.. include:: /manual/Layout/UIContentBlockAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIContentBlock**

:Syntax:
 
::

    {% UIContentBlock Type {Parameters} %}
        Content Goes Here
    {% EndUIContentBlock %}

:Type:

+------------------------------------------+-------------------------------------------------------------------+
| :ref:`Standard <UIContentBlockStandard>` | No comment                                                        |
+------------------------------------------+-------------------------------------------------------------------+
| :ref:`ForCode <UIContentBlockForCode>`   | Used to display a block of code like <pre> but allows line break. |
+------------------------------------------+-------------------------------------------------------------------+

.. _UIContentBlockStandard:

UIContentBlock Standard
^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIContentBlock Standard {sId:'value', aContainerClasses:{name:value, name:value}} %}
        Content Goes Here
    {% EndUIContentBlock %}

:parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

.. _UIContentBlockForCode:

UIContentBlock ForCode
^^^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIContentBlock ForCode {sCode:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIContentBlock %}

:parameters:

+-------+--------+-----------+------+--+
| sCode | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

UIContentBlock common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   | relative path of a CSS file to add                         |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   | relative path of a JS file to add                          |
+-------------------+----------+------------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value'] |
+-------------------+----------+------------------------------------------------------------+
| DeferredBlocks    | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                |
+-------------------+----------+------------------------------------------------------------+
| SubBlocks         | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Layout/UIContentBlockFooter.rst
