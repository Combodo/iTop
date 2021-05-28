.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

FieldSet
========

Class FieldSet

----

.. include:: /manual/Component/FieldSet/FieldSetAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIFieldSet**

:Syntax:
 
::

    {% UIFieldSet Type {Parameters} %}
        Content Goes Here
    {% EndUIFieldSet %}

:Type:

+----------+------------+
| Standard | No comment |
+----------+------------+

:FieldSet *Standard* parameters:

+---------+--------+-----------+------+--+
| sLegend | string | mandatory |      |  |
+---------+--------+-----------+------+--+
| sId     | string | optional  | NULL |  |
+---------+--------+-----------+------+--+

:FieldSet common parameters:

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

.. include:: /manual/Component/FieldSet/FieldSetFooter.rst
