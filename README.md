# Setting
`php atrisan migrate`

$baseUrl - `baseUrl/api`

# User

## signup

```
url: $baseUrl/signup
method: POST
request: {
    data: {
        fio: user
        login: required|string|min:5|unique
        password: required|string|min:8
    }
}
response: {
    201: {
        data: {
            fio: string,
            login: string,
            updated_at: timestamp,
            created_at: timestamp,
            id: integer
        },
        access_token: string
    }
    422: {
        message: {
            [field]: sting
        }
    }
}

```

## login

```
url: $baseUrl/login
method: POST
request: {
    data: {
        login: string
        password: string
    }
}
response: {
    200: {
        data: {
            fio: string,
            login: string,
            updated_at: timestamp,
            created_at: timestamp,
            id: integer
            role: integer
        },
        access_token: string
    }
    404: {
        message: string
    }
}
```

## profile

```
url: $baseUrl/profile
method: GET
headers: {
    authorization: $access_token
}
response: {
    200: {
        data: {
            fio: string,
            login: string,
            updated_at: timestamp,
            created_at: timestamp,
            id: integer
            role: integer
        },
    }
    401: {
        message: string
    }
}
```

## logout 

```
url: $baseUrl/logout
method: GET
headers: {
    authorization: $access_token
}
response: {
    200: {
        data: {
            fio: string,
            login: string,
            updated_at: timestamp,
            created_at: timestamp,
            id: integer
            role: integer
        },
    }
    401: {
        message: string
    }
}
```
---

# Category

## get all

```
url: $baseUrl/category
method: GET
response: {
    200: {
        data: {
            id: integer
            name: strung
            updated_at: timestamp,
            created_at: timestamp,
        },
    }
}
```

## create

```
url: $baseUrl/category
method: POST
headers: {
    authorization: $access_token
}
request: {
    name: required|string|min:5
}
response: {
    200: {
        data: {
            id: integer
            name: strung
            updated_at: timestamp,
            created_at: timestamp,
        },
    }
    403: {
         message: string
    }
    422: {
        message: {
            [field]: sting
        }
    }
}
```

## update

```
url: $baseUrl/category/{id}
method: PATCH
headers: {
    authorization: $access_token
}
request: {
    name: required|string|min:5
}
response: {
    200: {
        data: {
            id: integer
            name: strung
            updated_at: timestamp,
            created_at: timestamp,
        },
    }
    403: {
         message: string
    }
    422: {
        message: {
            [field]: sting
        }
    }
}
```

## delete

```
url: $baseUrl/category/{id}
method: DELETE
headers: {
    authorization: $access_token
}
response: {
    200: {
        message: string
    }
    403: {
         message: string
    }
}
```
