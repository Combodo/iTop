.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Input:

Input
=====

Class Input

----

.. include:: /manual/Component/Input/InputAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIInput**

:Syntax:

.. code-block:: twig

    {% UIInput Type {Parameters} %}

:Type:

+---------------------------------------------------+------------------------------------------------------------------------------------+
| :ref:`ForHidden <InputForHidden>`                 | No comment                                                                         |
+---------------------------------------------------+------------------------------------------------------------------------------------+
| :ref:`Standard <InputStandard>`                   | No comment                                                                         |
+---------------------------------------------------+------------------------------------------------------------------------------------+
| :ref:`ForInputWithLabel <InputForInputWithLabel>` | @see Field component that is better adapter when dealing with a standard iTop form |
+---------------------------------------------------+------------------------------------------------------------------------------------+

.. _InputForHidden:

Input ForHidden
^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIInput ForHidden {sName:'value', sValue:'value', sId:'value'} %}

:parameters:

+--------+--------+-----------+------+--+
| sName  | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sValue | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

.. _InputStandard:

Input Standard
^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIInput Standard {sType:'value', sName:'value', sValue:'value', sId:'value'} %}

:parameters:

+--------+--------+-----------+------+--+
| sType  | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sName  | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sValue | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

.. _InputForInputWithLabel:

Input ForInputWithLabel
^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIInput ForInputWithLabel {sLabel:'value', sInputName:'value', sInputValue:'value', sInputId:'value', sInputType:'value'} %}

:parameters:

+-------------+--------+-----------+--------+--+
| sLabel      | string | mandatory |        |  |
+-------------+--------+-----------+--------+--+
| sInputName  | string | mandatory |        |  |
+-------------+--------+-----------+--------+--+
| sInputValue | string | optional  | NULL   |  |
+-------------+--------+-----------+--------+--+
| sInputId    | string | optional  | NULL   |  |
+-------------+--------+-----------+--------+--+
| sInputType  | string | optional  | 'type' |  |
+-------------+--------+-----------+--------+--+

Input common parameters
^^^^^^^^^^^^^^^^^^^^^^^
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
| IsChecked         |        |                                                            |
+-------------------+--------+------------------------------------------------------------+
| IsDisabled        | bool   |                                                            |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+
| IsReadonly        | bool   |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Name              | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Placeholder       | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Type              | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Value             | string |                                                            |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/InputFooter.rst
