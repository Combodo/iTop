.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _FileSelect:

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

+--------------------------------------+------------+
| :ref:`Standard <FileSelectStandard>` | No comment |
+--------------------------------------+------------+

.. _FileSelectStandard:

FileSelect Standard
^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIFileSelect Type Standard {sName:'value', sId:'value'} %}

:parameters:

+-------+--------+-----------+------+--+
| sName | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

FileSelect common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+--------+------------------------------------------------------------+
| AddCSSClass       | string | CSS class to add to the generated html block               |
+-------------------+--------+------------------------------------------------------------+
| AddCSSClasses     | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| AddCssFileRelPath | string | relative path of a CSS file to add                         |
+-------------------+--------+------------------------------------------------------------+
| AddHtml           | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddJsFileRelPath  | string | relative path of a JS file to add                          |
+-------------------+--------+------------------------------------------------------------+
| ButtonText        | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| CSSClasses        | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| DataAttributes    | array  | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------+------------------------------------------------------------+
| FileName          |        |                                                            |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+
| ShowFilename      | bool   |                                                            |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/FileSelect/FileSelectFooter.rst
