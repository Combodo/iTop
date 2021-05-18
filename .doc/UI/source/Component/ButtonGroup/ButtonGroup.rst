.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

ButtonGroup
===========

*In Progress*

----

Output Result
-------------

:Example ButtonGroups:


----

Twig Tag
--------

:Tag: **UIButtonGroup**

:Syntax:

::

    {% UIButtonGroup Type {Parameters} %}
        Content Goes Here
    {% EndUIButtonGroup %}

:Type:

+------------------------------+-----------------------------------------------------+
| *Standard*                   | Create a *Default ButtonGroup*                      |
+------------------------------+-----------------------------------------------------+

:ButtonGroup common parameters:

+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | ID of the HTML block             |
+-------------------+--------+-----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------

Example to generate a ButtonGroup::

    {% UIButtonGroup Type {Parameters} %}
        Content Goes Here
    {% EndUIButtonGroup %}

