# 👤 User Service API

**Base URL:** `http://localhost:8000`

## Deskripsi Layanan

User Service adalah **gerbang utama** dan **fondasi keamanan** dari platform **House of Zama**. Layanan ini menangani:

* 🔐 **Otentikasi**: Registrasi, login, dan logout pengguna.
* 🛡️ **Otorisasi**: Mengelola peran pengguna (Admin dan User).
* 🗃️ **Manajemen Data**: Menyediakan fitur CRUD untuk data master pengguna.

Dibangun menggunakan **Laravel 10** dan terkoneksi dengan **database MySQL**, layanan ini menjadi **source of truth** untuk seluruh informasi pengguna di sistem.

---

## 🛠️ Daftar Endpoint

### `POST /api/register`

**Deskripsi:**
Mendaftarkan klien baru ke platform.
📌 Endpoint ini bersifat **publik** dan **tidak memerlukan autentikasi**.

**Contoh Request Body:**

```json
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "securePassword123"
}
```

---

### `POST /api/login`

**Deskripsi:**
Melakukan otentikasi pengguna berdasarkan kredensial.
Jika berhasil, mengembalikan `access_token` (Bearer Token) untuk digunakan pada endpoint yang terproteksi.

**Contoh Request Body:**

```json
{
  "email": "johndoe@example.com",
  "password": "securePassword123"
}
```

**Response:**

```json
{
  "access_token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOi...",
  "token_type": "Bearer"
}
```

---

### `GET /api/me`

**Deskripsi:**
Mengambil data lengkap dari pengguna yang sedang login.
📌 **Wajib menyertakan Bearer Token** di header `Authorization`.

---

### `POST /api/logout`

**Deskripsi:**
Menghapus token otentikasi yang sedang digunakan.
Secara efektif, ini akan **mengeluarkan pengguna dari sesi aktif**.

📌 **Wajib Bearer Token.**

---

### `GET /api/users`

**Deskripsi:**
🔒 Hanya untuk **Admin**
Mengambil seluruh data pengguna dengan **fitur paginasi**.

📌 Terproteksi & hanya bisa diakses oleh pengguna dengan peran `admin`.

---

### `GET /api/users/{id}`

**Deskripsi:**
🔒 \[Admin] Mengambil **detail pengguna spesifik** berdasarkan ID-nya.

---

### `PUT /api/users/{id}`

**Deskripsi:**
🔒 \[Admin] Memperbarui data pengguna seperti nama, email, atau password.

**Contoh Request Body:**

```json
{
  "name": "Jane Doe",
  "email": "janedoe@example.com"
}
```

---

### `DELETE /api/users/{id}`

**Deskripsi:**
🔒 \[Admin] Menghapus pengguna secara permanen dari sistem.

---

## 🔐 Autentikasi

Gunakan format Bearer Token pada header untuk endpoint yang terproteksi:

```
Authorization: Bearer {access_token}
```

---

## 📝 Catatan Tambahan

* Semua endpoint menggunakan format JSON.
* Autentikasi menggunakan token berbasis **JWT**.
* Role pengguna (`admin` / `user`) menentukan hak akses terhadap endpoint tertentu.

