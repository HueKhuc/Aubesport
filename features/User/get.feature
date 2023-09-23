@users
Feature:
    In order to get the information of users 
    As a developer 
    I need to send a get request

    Scenario: I need the information of all the users
        When I send a get request to "/api/users"
        Then I should receive a status code 200

    Scenario: I need the information of an user with existing uuid
        Given there is an existant user with email "myEmail@gmail.com" 
        When I send a get request to "/api/users/abcd123456"
        Then I should receive a status code 200
    
    Scenario: I need the information of an user with a uuid doesn't exist
        When I send a get request to "/api/users/123"
        Then I should receive a status code 404