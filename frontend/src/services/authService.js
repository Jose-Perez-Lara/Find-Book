import axios from 'axios'
import { signInWithEmailAndPassword } from 'firebase/auth'
import { auth } from '@/firebase'

const API_URL = 'http://127.0.0.1:8000/api'

export const login = async (email, password) => {
  const userCredential = await signInWithEmailAndPassword(auth, email, password)
  const token = await userCredential.user.getIdToken()

  localStorage.setItem('token', token)


  const response = await axios.post(`${API_URL}/user`, {}, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  const user = {
    ...response.data.user, 
    token: token
  }

  return user
}

export const getUserWithToken = async () =>{
  const token = getToken()
  const response = await axios.post(`${API_URL}/user`, {}, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
  return response.data.user
}

export const register = async (user, uid, password) =>{
  return await axios.post(`${API_URL}/register`, {
      firebase_uid: uid,
      correo_electronico: user.email,
      nombre: user.name,
      password:password,
      telefono: user.telefono,
      rol_id: 1 
  })
  
} 

export const registerNegocio = async (negocio, uid, password) =>{
  const newUser = await axios.post(`${API_URL}/register`, {
      firebase_uid: uid,
      correo_electronico: negocio.email,
      nombre: negocio.name,
      password:password,
      telefono: negocio.telefono,
      rol_id: 2
  })
  const newNegocio = await axios.post(`${API_URL}/register-negocio`, 
    {
      usuario_id: newUser.data.id,
      categoria: negocio.nombre_negocio,
      nombre: negocio.nombre_negocio,
      descripcion: negocio.descripcion
    }
  );
}


export const logout = () => {
  localStorage.removeItem('token')
}

export const getToken = () => {
  return localStorage.getItem('token')
}

export const isAuthenticated = () => {
  return !!getToken()
}
