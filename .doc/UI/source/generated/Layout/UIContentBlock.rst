.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

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

+----------+-------------------------------------------------------------------+
| Standard | No comment                                                        |
+----------+-------------------------------------------------------------------+
| ForCode  | Used to display a block of code like <pre> but allows line break. |
+----------+-------------------------------------------------------------------+

:UIContentBlock *Standard* parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

:UIContentBlock *ForCode* parameters:

+-------+--------+-----------+------+--+
| sCode | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

:UIContentBlock common parameters:

+-------------------+----------+--------------------------------------------------------+
| AddCSSClass       | string   |                                                        |
+-------------------+----------+--------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code> |
+-------------------+----------+--------------------------------------------------------+
| AddCssFileRelPath | string   |                                                        |
+-------------------+----------+--------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                        |
+-------------------+----------+--------------------------------------------------------+
| AddHtml           | string   |                                                        |
+-------------------+----------+--------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                        |
+-------------------+----------+--------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                        |
+-------------------+----------+--------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code> |
+-------------------+----------+--------------------------------------------------------+
| DataAttributes    | array    |                                                        |
+-------------------+----------+--------------------------------------------------------+
| DeferredBlocks    | array    |                                                        |
+-------------------+----------+--------------------------------------------------------+
| IsHidden          | bool     |                                                        |
+-------------------+----------+--------------------------------------------------------+
| SubBlocks         | array    |                                                        |
+-------------------+----------+--------------------------------------------------------+

----

.. include:: /manual/Layout/UIContentBlockFooter.rst
