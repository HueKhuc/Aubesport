@users
Feature:
    In order to get the information of users 
    As a developer 
    I need to send a get request

    Scenario: I need the information of all the users
        When I send a get request to "/api/users"
        Then I should receive a status code 200
