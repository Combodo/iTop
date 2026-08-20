Removed from disk
  Yes : Installed
    No : Do not show extension .color_grey
    Yes : Forced uninstallation, unchecked & disabled .color_red #node1
  No : Mandatory & part of package
    Yes : Forced installation, checked & disabled .color_orange #node2
    No : Has sub options
      Yes : Sub option checked
        Yes : Sub option disabled
          Yes : checked & disabled .color_orange #node3
          No : force checked .color_yellow #node4
        #node16 No : (Dependency issue)
      No : Dependency issue
        Yes : Installed
          No : unchecked & disabled .color_orange #node5
          Yes : Force uninstall
            No : checked & disabled .color_orange #node6
            Yes : Selected
              Yes : checked & enabled .color_green #node7
              No : unchecked & enabled .color_green #node8
        No : Installed
          No : Selected
            No : unchecked & enabled .color_green #node9
            Yes : checked & enabled .color_green #node10
          Yes : Remote | cannot_be_uninstalled
            No : Selected
              No : unchecked & enabled .color_green #node11
              Yes : checked & enabled .color_green #node12
            Yes : Force uninstall
              No : checked & disabled .color_orange #node13
              Yes : Selected
                No : unchecked & enabled .color_green #node14
                Yes : checked & enabled .color_green #node15
