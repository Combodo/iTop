.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Spinner:

Spinner
=======

Class Spinner

----

.. include:: /manual/Component/Spinner/SpinnerAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UISpinner**

:Syntax:
 
::

    {% UISpinner Type {Parameters} %}

:Type:

+-----------------------------------+------------+
| :ref:`Standard <SpinnerStandard>` | No comment |
+-----------------------------------+------------+

.. _SpinnerStandard:

Spinner Standard
^^^^^^^^^^^^^^^^

:syntax:

::

    {% UISpinner Type Standard {sId:'value'} %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

Spinner common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^
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
| CSSClasses        | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| DataAttributes    | array  | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Spinner/SpinnerFooter.rst
