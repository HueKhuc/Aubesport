```mermaid
---
title: MCD du site AubEsport
---
erDiagram
    USER ||--|{ ADDRESS : have
    
    USER {
        int id
        string pseudo
        string bio
        string name
        string email UK "type email, unique"
        string password "hasher"
        date birthday
        boolean isAdmin
        boolean gender
    }
    
    ADDRESS {
        int id
        int number
        string line1
        int postalCode
        string city
    }

    USER ||--|{ IMAGE : own
    IMAGE {
        int id
        string link
    }

    USER ||--|{ GAME : prefer
    GAME {
        int id
        string name
    }

    ACTIVITY {
        int id
        string name "bill,inscriptionTournoix,achat"
    }

    USER ||--|{ TOURNAMENT : register
    TOURNAMENT {
        int id
        string name
        date date
        int score "????"
    }

    BILL {
        int id
        string name
    }

    MESSAGE {
        int id
        string email "user or not ?"
        string name
        string message
        date date
    }

    
```

<!-- RELATIONSHIP
    |o	o|	Zero or one
    ||	||	Exactly one
    }o	o{	Zero or more (no upper limit)
    }|	|{	One or more (no upper limit) 
-->