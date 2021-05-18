.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

UIContentBlock
==============

Add a content block.

Create a ``<div>`` only if container class or data attributes are provided or is hidden.

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

+------------------------------+-----------------------------------------------------+
| *Standard*                   | Create a default content block                      |
+------------------------------+-----------------------------------------------------+
| *ForCode*                    | Create a content block to display code              |
+------------------------------+-----------------------------------------------------+

:UIContentBlock common parameters:

+---------------------+--------+-----------+----------------------------------+
| *aContainerClasses* | array  | optional  | array of classes                 |
+---------------------+--------+-----------+----------------------------------+
| *sId*               | string | optional  | ID of the HTML block             |
+---------------------+--------+-----------+----------------------------------+

:ForCode specific parameters:

+--------------------+--------+-----------+------------------------------------------------+
| *sCode*            | string | mandatory | Provided code to display                       |
+--------------------+--------+-----------+------------------------------------------------+


:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------

::

    {% UIContentBlock Standard {aContainerClasses:["my-class", "my-other-class"], DataAttributes: {role: "my-role"}} %}
        Content Goes Here
    {% EndUIContentBlock %}

Will produce the following HTML::

    <div id="ibo-content-block-60a3a9c59d4b95-40516619" class="my-class my-other-class" data-role="my-role">
       Content Goes Here
    </div>

