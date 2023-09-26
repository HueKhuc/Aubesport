@users
Feature:
    In order to delete users 
    As an admin 
    I need to send a delete request

    Scenario: Send a delete request
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a delete request to "/api/users/a245f890-accf-4295-a00a-522732682fdc"
        Then I should receive a status code 200
        And the node "deletedAt" of the reponse should not be null
