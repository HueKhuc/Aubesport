```mermaid
---
title: MCD du site AubEsport
---
erDiagram
    USER ||--o{ ADDRESS : have
    
    USER }o--o{ ROLE : have

    ROLE {
        string uuid PK
        string label "admin, user, ..."
    }

    USER {
        string uuid PK
        string pseudo
        string bio
        string name
        string email UK "type email, unique"
        string favouriteGame
        date birthday
        string gender "feminin, masculin et autre"
        date modifiedAt
        date createdAt
        date deletedAt
    }
    
    USER ||--o{ AUTHENTICATION : have
    AUTHENTICATION {
        string uuid PK
        string password
        date modifiedAt
        date createdAt
    }

    ADDRESS {
        string uuid PK
        int number
        string line1
        int postalCode
        string city
    }

    USER ||--|| IMAGE : own
    IMAGE {
        string uuid PK
        string link
    }


    USER }o--o{ TOURNAMENT : register
    TOURNAMENT {
        string uuid PK
        string name
        date date
        int score "????"
    }



    
```

<!-- RELATIONSHIP
    |o	o|	Zero or one
    ||	||	Exactly one
    }o	o{	Zero or more (no upper limit)
    }|	|{	One or more (no upper limit) 

        CONTACT {
        int id
        string email "user or not ?"
        string name
        string message
        date date
    }
-->