Feature: Test itop
  In order access the application, users must be able to log in

  @pro @com
  Scenario: config voit toutes les organisations
    Given there is an iTop installed with the standard datamodel and sample data 'default'
    And I login as "config/config"
    And I have a valid user account 'config/config'
    And I wait for 2 seconds
    Then I follow "Déconnexion"
