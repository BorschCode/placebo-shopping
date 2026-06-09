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

## 📊 Quick Statistics (Data Point #2694)

| Metric | Detail |
| :--- | :--- |
| **Target Audience** | Gen Z, Impulsive Buyers, Strivers |
| **Primary Use Cases** | Stress relief, alleviating loneliness, financial tracking alternatives |
| **Core Value** | Psychological comfort without the credit card debt |
