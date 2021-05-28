.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

FileSelect
==========

Class FileSelect

----

.. include:: /manual/Component/Input/FileSelect/FileSelectAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIFileSelect**

:Syntax:
 
::

    {% UIFileSelect Type {Parameters} %}

:Type:

+----------+------------+
| Standard | No comment |
+----------+------------+

:FileSelect *Standard* parameters:

+-------+--------+-----------+------+--+
| sName | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

:FileSelect common parameters:

+-------------------+--------+--------------------------------------------------------+
| AddCSSClass       | string |                                                        |
+-------------------+--------+--------------------------------------------------------+
| AddCSSClasses     | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code> |
+-------------------+--------+--------------------------------------------------------+
| AddCssFileRelPath | string |                                                        |
+-------------------+--------+--------------------------------------------------------+
| AddHtml           | string |                                                        |
+-------------------+--------+--------------------------------------------------------+
| AddJsFileRelPath  | string |                                                        |
+-------------------+--------+--------------------------------------------------------+
| ButtonText        | string |                                                        |
+-------------------+--------+--------------------------------------------------------+
| CSSClasses        | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code> |
+-------------------+--------+--------------------------------------------------------+
| DataAttributes    | array  |                                                        |
+-------------------+--------+--------------------------------------------------------+
| FileName          |        |                                                        |
+-------------------+--------+--------------------------------------------------------+
| IsHidden          | bool   |                                                        |
+-------------------+--------+--------------------------------------------------------+
| ShowFilename      | bool   |                                                        |
+-------------------+--------+--------------------------------------------------------+

----

.. include:: /manual/Component/Input/FileSelect/FileSelectFooter.rst
