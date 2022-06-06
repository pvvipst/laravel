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
        fio: required|string|min:5
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

## product in category

```
url: $baseUrl/category/product/{id}
method: GET
response: {
    200: {
        data: {
            id: number,
            category_id: string,
            name: string,
            price: number,
            description: string,
            size: string,
            count: number,
            created_at: timestamp,
            updated_at: timestamp
        }[]
    }
    404: {
         message: string
    }
}
```

## all

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
        }[]
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
    201: {
        data: {
            id: integer
            name: strung
            updated_at: timestamp,
            created_at: timestamp,
        },
    }
    401: {
        message: string
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
    401: {
        message: string
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
    401: {
        message: string
    }
    403: {
         message: string
    }
}
```

# Product

## all

```
url: $baseUrl/product
method: GET
response: {
    200: {
        data: {
            id: number,
            category_id: string,
            name: string,
            price: number,
            description: string,
            size: string,
            count: number,
            created_at: timestamp,
            updated_at: timestamp
        }[]
    }
}
```

## create

```
url: $baseUrl/product
method: POST
headers: {
    authorization: $access_token
}
request: {
    category_id: 'required|integer
    name: required|string|min:5
    price: required|integer
    descriptio: required|string|min:15
    count: required|integer
    size: required|string
}
response:{
    201: {
        data: {
            id: number,
            category_id: string,
            name: string,
            price: number,
            description: string,
            size: string,
            count: number,
            created_at: timestamp,
            updated_at: timestamp
        }
    }
    401: {
        message: string
    }
    403: {
         message: string
    }
    404: {
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
url: $baseUrl/product/{id}
method: PATCH
headers: {
    authorization: $access_token
}
request: {
    category_id: 'required|integer
    name: required|string|min:5
    price: required|integer
    descriptio: required|string|min:15
    count: required|integer
    size: required|string
}
response: {
    200: {
        data: {
            id: number,
            category_id: string,
            name: string,
            price: number,
            description: string,
            size: string,
            count: number,
            created_at: timestamp,
            updated_at: timestamp
        }
    }
    401: {
        message: string
    }
    403: {
         message: string
    }
    404: {
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
url: $baseUrl/product/{id}
method: DELETE
headers: {
    authorization: $access_token
}
response: {
    200: {
        message: string
    }
    401: {
        message: string
    }
    403: {
         message: string
    }
    404: {
         message: string
    }
}
```

# Order

## all

```
url: $baseUrl/order
method: GET
headers: {
    authorization: $access_token
}
response: {
    200: {
        data: {
            items: {
               product_id: number
               count: number 
            }[]  
            status: number
        }[]
    }
    401: {
        message: string
    }
}
```

## create

```
url: $baseUrl/order
method: POST
headers: {
    authorization: $access_token
}
response: {
    200: {
        data: {
            product_id: number
            count: number
        }[]
    }
    401: {
        message: string
    }
    404: {
        message: string
    }
}
```

## change status

```
url: $baseUrl/change-status
method: POST
headers: {
    authorization: $access_token
}
request: {
    status: required|integer
}
response: {
    200: {
        data: {
            product_id: number
            count: number
        }[]
    }
    401: {
        message: string
    }
    404: {
        message: string
    }
}
```
