# StreamVibe API Authentication Documentation

## Base URL
http://127.0.0.1:8000/api

## Endpoints

### Registration
`POST /register`

```javascript
Request Body:

{
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "password": "string",
  "password_confirmation": "string"
}



Success Response (201 Created):

{
  "message": "User registered successfully"
}
```
 
```javascript
  ### Login
    `POST /login`

Request Body:

{
  "email": "string",
  "password": "string"
}


Success Response (200 OK):

{
  "user": {
    "id": "number",
    "name": "string",
    "email": "string"
  },
  "token": "string"
}
```


```javascript
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
```

```javascript

## Usage in React Components


import { register, login } from '../api';

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
    console.log('Logged in user:', result.user);
    console.log('Auth token:', result.token);
  } catch (error) {
    console.error(error);
  }
};
```

## Error Handling
The API returns appropriate error messages and status codes for invalid requests. Handle these errors in your front-end application to provide user feedback.

## Authentication
After successful login, store the returned token securely (e.g., in HttpOnly cookies or local storage). Include this token in the Authorization header for subsequent authenticated requests.
