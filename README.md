# WP Admin Search Filter

A WordPress admin plugin that demonstrates **search and pagination**
on custom database records using the WordPress `$wpdb` API.

This project focuses on building clean, secure, and scalable
admin-side features following WordPress best practices.

---

## ✨ Features

- Admin dashboard page with searchable table
- Search across name, email, and message fields
- Pagination using LIMIT and OFFSET
- Secure queries using `$wpdb->prepare()`
- Clean admin UI with WordPress styles

---

## 🔍 How It Works

- Uses a custom admin menu page
- Accepts search input via query parameters
- Builds dynamic SQL `WHERE` clauses safely
- Implements pagination with `paginate_links()`

---

## 🧠 Learning Outcomes

- Practical use of `$wpdb`
- Writing secure and performant SQL queries
- Handling admin-side GET parameters
- Implementing pagination in WordPress admin pages

---

## 🚀 Installation

1. Clone or download this repository
2. Copy the plugin folder to:

wp-content/plugins/

3. Activate **WP Admin Search Filter** from WordPress Admin
4. Open **Admin Search** from the left sidebar

---

## 📌 Notes

This plugin reuses the `wpsc_messages` table created by the
**WP Secure Contact** plugin for demonstration purposes.

---

## 👤 Author

**Mohammad Shadullah**

---

## 📄 License

This project is intended for learning and educational purposes.
