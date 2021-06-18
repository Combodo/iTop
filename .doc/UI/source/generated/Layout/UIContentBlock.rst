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

.. code-block:: twig

    {% UIContentBlock Type {Parameters} %}
        Content Goes Here
    {% EndUIContentBlock %}

:Type:

+--------------------------------------------------------+-------------------------------------------------------------------+
| :ref:`Standard <UIContentBlockStandard>`               | No comment                                                        |
+--------------------------------------------------------+-------------------------------------------------------------------+
| :ref:`ForCode <UIContentBlockForCode>`                 | Used to display a block of code like <pre> but allows line break. |
+--------------------------------------------------------+-------------------------------------------------------------------+
| :ref:`ForPreformatted <UIContentBlockForPreformatted>` | No comment                                                        |
+--------------------------------------------------------+-------------------------------------------------------------------+

.. _UIContentBlockStandard:

UIContentBlock Standard
^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

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

.. code-block:: twig

    {% UIContentBlock ForCode {sCode:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIContentBlock %}

:parameters:

+-------+--------+-----------+------+--+
| sCode | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

.. _UIContentBlockForPreformatted:

UIContentBlock ForPreformatted
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIContentBlock ForPreformatted {sCode:'value', sId:'value'} %}
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

+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClass                 | string   | CSS class to add to the generated html block               |
+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClasses               | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath           | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddDeferredBlock            | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddHtml                     | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath            | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleCssFilesRelPaths | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleJsFilesRelPaths  | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddSubBlock                 | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| CSSClasses                  | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| DataAttributes              | array    | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+----------+------------------------------------------------------------+
| DeferredBlocks              | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| IsHidden                    | bool     | Indicates if the block is hidden by default                |
+-----------------------------+----------+------------------------------------------------------------+
| SubBlocks                   | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Layout/UIContentBlockFooter.rst
