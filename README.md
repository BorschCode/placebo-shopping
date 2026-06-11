# 🛒 placebo-shopping (Virtual Consumption App)

> **"All the dopamine of shopping, none of the buyers' remorse."**

Inspired by the rising Gen Z trend in South Korea dubbed **"Dopamine Sites,"** this project is a behavioral science sandbox. It simulates the exact UI/UX loop of a premium e-commerce and food delivery platform, but with a catch: **everything is entirely fake.** Users can impulse-buy luxury items, mock-checkout, and track non-existent delivery drivers in real-time—all to trigger a dopamine release, relieve loneliness, or curb actual spending habits.

---

## 🇰🇷 Trend Spotlight: The South Korean "Fake Consumption" Wave

According to *The Korea Times*, South Korean Gen Z youth are turning to simulated web environments to cope with stress and economic pressures. 

### 🧠 The Psychology Behind It:
* **The Mechanics:** Users create virtual shopping carts, simulate checkouts, and actively track a "phantom" delivery driver on a live map interface.
* **The Goal:** It tricks the brain's reward center into feeling the rush of consumption, providing temporary psychological comfort and successfully suppressing real-world impulsive shopping desires.

---

## 🛠️ Planned Architecture

### 1. The Dopamine Cart (`/src/components/Cart`)
A hyper-realistic cart that totals up massive amounts of money, complete with animated coupons and "Free Shipping" banners to maximize excitement.

### 2. The Ghost Checkout (`/src/controllers/CheckoutController`)
A zero-friction checkout experience. No real credit cards allowed. It triggers custom animations, sound effects (like a cash register chime), and confetti to mimic the peak rush of spending.

### 3. The Phantom Delivery Map (`/src/components/Map`)
Using Mock GPS data or Canvas animations, this simulates a delivery driver picking up your "food" or "goods" and moving toward your address on a countdown timer.

---

## 🧪 Test Fixtures

Load sample data into the database for development and testing:

```bash
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```

### Seed Accounts

| Email | Password | Role | Theme |
| :--- | :--- | :--- | :--- |
| `admin@placebo.local` | `password` | `ROLE_ADMIN` | OLX |
| `user1@example.com` | `password` | `ROLE_USER` | AutoRia |
| `user2@example.com` | `password` | `ROLE_USER` | Experinza |
| `user3@example.com` … `user20@example.com` | `password` | `ROLE_USER` | cycling OLX → AutoRia → Experinza |

### Seeded Data

| Dataset | Count | Notes |
| :--- | :--- | :--- |
| Users + Profiles | 21 | Faker-generated Ukrainian names, cities, phones |
| OLX categories | 9 | Електроніка, Авто, Нерухомість, Одяг, Дитячий світ, Робота, Послуги, Тварини, Хобі |
| AutoRia categories | 8 | Легкові, Мотоцикли, Вантажівки, Спецтехніка, Автобуси, Запчастини, Аксесуари, Водний транспорт |
| Experinza categories | 8 | Піца, Бургери, Суші, Напої, Десерти, Веганське, Піта/Шаурма, Здорове |
| Listings | 60 | 20 per theme, random prices and picsum.photos images |
| Conversations | 15 | 5 per theme, between distinct buyers and sellers |
| Messages | ~75 | 3–7 Ukrainian-language messages per conversation |
| Fake Orders | 20 | Mixed statuses, random 20–90 min delivery estimates |

---

## 📊 Quick Statistics (Data Point #2694)

| Metric | Detail |
| :--- | :--- |
| **Target Audience** | Gen Z, Impulsive Buyers, Strivers |
| **Primary Use Cases** | Stress relief, alleviating loneliness, financial tracking alternatives |
| **Core Value** | Psychological comfort without the credit card debt |
