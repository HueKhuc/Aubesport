@users
Feature:
    In order to delete users 
    As an admin 
    I need to send a delete request

    Scenario: Send a delete request to the uri with the exact uuid
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a delete request to "/api/users/a245f890-accf-4295-a00a-522732682fdc"
        Then I should receive a status code 204
        And the field "deletedAt" in the database of the user having the uuid "a245f890-accf-4295-a00a-522732682fdc" should not be null

    Scenario: Send a delete request to the uri with the wrong uuid
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a delete request to "/api/users/a245f890-accf-4295-a00a-522732682fd8" 
        Then I should receive a status code 400