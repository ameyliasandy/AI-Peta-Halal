from locust import HttpUser, task, between
from bs4 import BeautifulSoup
import re

# =====================================================
# KONFIGURASI
# =====================================================

RESTORAN_ID = 23
MENU_ID = 113

PENCARI = {
    "email": "saskiairawan1506@gmail.com",
    "password": "1234567",
}

PEMILIK = {
    "email": "saskia17@gmail.com",
    "password": "1234567",
}

ADMIN = {
    "email": "admin@halalfood.com",
    "password": "Admin@123",
}


# =====================================================
# BASE USER
# =====================================================

class BaseUser(HttpUser):

    wait_time = between(1, 3)

    csrf_token = None

    def get_csrf(self, url="/login"):
        """
        Mengambil CSRF token dari halaman Laravel
        """

        response = self.client.get(url, name="GET Login Page")

        soup = BeautifulSoup(response.text, "html.parser")

        token = soup.find("input", {"name": "_token"})

        if token:
            self.csrf_token = token["value"]
            return self.csrf_token

        # fallback jika token ada di meta
        m = re.search(
            r'<meta name="csrf-token" content="([^"]+)"',
            response.text
        )

        if m:
            self.csrf_token = m.group(1)
            return self.csrf_token

        print("CSRF Token tidak ditemukan")

        return None

    def login(self, email, password):

        token = self.get_csrf("/login")

        payload = {
            "_token": token,
            "email": email,
            "password": password
        }

        with self.client.post(
            "/login",
            data=payload,
            allow_redirects=False,
            catch_response=True,
            name="POST Login"
        ) as response:

            if response.status_code in [302, 303]:
                response.success()
            else:
                response.failure(
                    f"Gagal login ({response.status_code})"
                )

    def logout(self):

        token = self.get_csrf("/")

        payload = {
            "_token": token
        }

        self.client.post(
            "/logout",
            data=payload,
            name="POST Logout"
        )
# =====================================================
# PENCARI USER
# =====================================================

class PencariUser(BaseUser):

    weight = 8

    def on_start(self):
        """
        Dipanggil saat virtual user mulai.
        """
        self.login(
            PENCARI["email"],
            PENCARI["password"]
        )

    def on_stop(self):
        """
        Logout ketika virtual user selesai.
        """
        self.logout()

    @task(2)
    def home(self):
        self.client.get(
            "/",
            name="Home"
        )

    @task(3)
    def dashboard(self):
        self.client.get(
            "/dashboard",
            name="Dashboard User"
        )

    @task(5)
    def rekomendasi(self):
        self.client.get(
            "/rekomendasi",
            name="Rekomendasi"
        )

    @task(4)
    def detail_restoran(self):
        self.client.get(
            f"/restoran/{RESTORAN_ID}",
            name="Detail Restoran"
        )

    @task(2)
    def favorit(self):

        token = self.get_csrf(
            f"/restoran/{RESTORAN_ID}"
        )

        self.client.post(

            "/favorit/toggle",

            data={
                "_token": token,
                "id_restoran": RESTORAN_ID
            },

            name="Toggle Favorit"

        )

    @task(2)
    def ulasan(self):

        token = self.get_csrf(
            f"/restoran/{RESTORAN_ID}"
        )

        self.client.post(

            "/ulasan",

            data={
                "_token": token,
                "id_restoran": RESTORAN_ID,
                "rating": 5,
                "komentar": "Performance Testing Locust"
            },

            name="Tambah Ulasan"

        )
# =====================================================
# PEMILIK USER
# =====================================================

class PemilikUser(BaseUser):

    weight = 2

    def on_start(self):
        self.login(
            PEMILIK["email"],
            PEMILIK["password"]
        )

    def on_stop(self):
        self.logout()

    @task(2)
    def dashboard(self):
        self.client.get(
            "/pemilik/dashboard",
            name="Pemilik Dashboard"
        )

    @task(3)
    def halaman_toko(self):
        self.client.get(
            "/pemilik/toko",
            name="Halaman Toko"
        )

    @task(2)
    def detail_toko(self):
        self.client.get(
            f"/pemilik/toko/{RESTORAN_ID}",
            name="Detail Toko"
        )

    @task(2)
    def tambah_menu(self):

        token = self.get_csrf("/pemilik/toko")

        payload = {
            "_token": token,
            "nama_menu": "Menu Test Locust",
            "harga": 25000,
            "deskripsi": "Performance Testing",
            "tersedia": 1
        }

        self.client.post(
            "/pemilik/toko/menu",
            data=payload,
            name="Tambah Menu"
        )

    @task(2)
    def edit_menu(self):

        token = self.get_csrf("/pemilik/toko")

        payload = {
            "_token": token,
            "nama_menu": "Menu Edit Locust",
            "harga": 27000,
            "deskripsi": "Edited",
            "tersedia": 1
        }

        self.client.put(
            f"/pemilik/toko/menu/{MENU_ID}",
            data=payload,
            name="Edit Menu"
        )

    @task(1)
    def toggle_menu(self):

        token = self.get_csrf("/pemilik/toko")

        self.client.post(
            f"/pemilik/toko/menu/{MENU_ID}/toggle",
            data={
                "_token": token
            },
            name="Toggle Menu"
        )
# =====================================================
# ADMIN USER
# =====================================================

class AdminUser(BaseUser):

    weight = 1

    def on_start(self):
        self.login(
            ADMIN["email"],
            ADMIN["password"]
        )

    def on_stop(self):
        self.logout()

    @task(3)
    def dashboard(self):
        self.client.get(
            "/admin/index",
            name="Admin Dashboard"
        )

    @task(3)
    def daftar_restoran(self):
        self.client.get(
            "/admin/restoran",
            name="Daftar Restoran"
        )

    @task(2)
    def detail_restoran(self):
        self.client.get(
            f"/admin/restoran/{RESTORAN_ID}",
            name="Admin Detail Restoran"
        )

    @task(1)
    def export_csv(self):
        self.client.get(
            "/admin/restoran/export-csv",
            name="Export CSV"
        )

    @task(2)
    def update_restoran(self):

        token = self.get_csrf(
            f"/admin/restoran/{RESTORAN_ID}"
        )

        payload = {
            "_token": token,

            "nama_restoran": "Warung PETHA Testing",

            "alamat": "Batam",

            "status_verifikasi": "terverifikasi",

            "tipe_halal": "certified",

            "catatan": "Performance Testing"
        }

        self.client.post(
            f"/admin/restoran/{RESTORAN_ID}/update",
            data=payload,
            name="Update Restoran"
        )