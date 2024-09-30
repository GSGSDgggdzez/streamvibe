# StreamVibe API Authentication Documentation

## Base URL
http://127.0.0.1:8000/api

## Endpoints

### Registration
`POST /register`

```JS
Request Body:

{
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "password": "string",
}

Success Response (201 Created):

{
  "message": "User registered successfully"
}

```
 
```JS

### Login
`POST /login`

Request Body:

{
  "email": "string",
  "password": "string"
}

Success Response (200 OK):

{
  "message": "Logged in successfully"
}



### Verify Token
`GET /verify-token`

Success Response (200 OK):

{
  "message": "Token is valid"
}

Token Refresh Response (200 OK):

{
  "message": "Token refreshed"
}
```

```JS
## Implementation in Next.js

import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  withCredentials: true,
});

export const register = async (userData) => {
  try {
    const response = await api.post('/register', userData);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};

export const login = async (credentials) => {
  try {
    const response = await api.post('/login', credentials);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};

export const verifyToken = async () => {
  try {
    const response = await api.get('/verify-token');
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};
```


```JS
## Usage in React Components

import { register, login, verifyToken } from '../api';

// Registration
const handleRegister = async (userData) => {
  try {
    const result = await register(userData);
    console.log(result.message);
  } catch (error) {
    console.error(error);
  }
};

// Login
const handleLogin = async (credentials) => {
  try {
    const result = await login(credentials);
    console.log(result.message);
  } catch (error) {
    console.error(error);
  }
};

// Verify Token
const checkTokenValidity = async () => {
  try {
    const result = await verifyToken();
    console.log(result.message);
  } catch (error) {
    console.error(error);
  }
};
```

```JS
## CSRF Protection with Laravel Sanctum

1. Set up an Axios instance for your API requests:


import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  withCredentials: true, // Important for Sanctum to work correctly
});

// Before making any API calls, ensure you've obtained the CSRF cookie:
async function getCsrfCookie() {
  await api.get('/sanctum/csrf-cookie');
}

// Use this function before your login or registration requests:
async function login(credentials) {
  await getCsrfCookie();
  const response = await api.post('/api/login', credentials);
  return response.data;
}

async function register(userData) {
  await getCsrfCookie();
  const response = await api.post('/api/register', userData);
  return response.data;
}

async function verifyToken() {
  const response = await api.get('/api/verify-token');
  return response.data;
}

```

## Error Handling
The API returns appropriate error messages and status codes for invalid requests. Handle these errors in your front-end application to provide user feedback.

## Authentication
After successful login, the API sets the authentication token in an HTTP-only cookie. This token is automatically included in subsequent requests. Use the /verify-token endpoint to check the token's validity and refresh it if necessary.
