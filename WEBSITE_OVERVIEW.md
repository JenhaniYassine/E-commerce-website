# 2WHY E-Commerce Platform - Comprehensive Overview

## 📋 Table of Contents
1. [Project Description](#project-description)
2. [Core Functions & Features](#core-functions--features)
3. [Technology Stack](#technology-stack)
4. [UML Architecture](#uml-architecture)
5. [Database Schema](#database-schema)
6. [User Workflows](#user-workflows)
7. [Implementation Status](#implementation-status)

---

## 🎯 Project Description

### Overview
**2WHY** is a modern, intelligent e-commerce platform built with Symfony 5.4 and modern web technologies. The platform emphasizes clarity, trust, and data-driven decision-making through a beautifully minimalist interface.

### Brand Mission
**"Clarity Through Analysis"** - We help users understand the "why" behind every decision, delivering trustworthy, intelligent solutions.

### Target Users
- Online shoppers seeking quality products
- Users interested in product reviews and recommendations
- Customers requiring robust order tracking
- Support-seeking individuals with product questions

### Key Values
- **Futuristic**: Modern, forward-thinking, tech-savvy design
- **Trustworthy**: Professional, reliable, secure transactions
- **Intelligent**: Data-driven insights and recommendations
- **Minimalist**: Clean, uncluttered user interface
- **Accessible**: Intuitive, user-friendly for all

---

## 🔧 Core Functions & Features

### 1. **User Management**
| Feature | Description | Status |
|---------|-------------|--------|
| User Registration | Email-based account creation with verification | ✅ Complete |
| Email Verification | Verify email ownership before account activation | ✅ Complete |
| Login/Authentication | Secure credential-based login system | ✅ Complete |
| Password Reset | Secure password recovery via email tokens | ✅ Complete |
| User Roles & Permissions | Admin, Customer, and User roles support | ✅ Complete |
| Account Banning | Admin ability to ban users for policy violations | ✅ Complete |
| Profile Management | Edit personal information, contact details | ✅ Complete |
| Dashboard | User personal dashboard with stats & activity | ✅ Complete |

### 2. **Product Catalog**
| Feature | Description | Status |
|---------|-------------|--------|
| Product Listings | Browse products with filtering and sorting | ✅ Complete |
| Product Categories | Organized product taxonomy | ✅ Complete |
| Product Details | Comprehensive product information pages | ✅ Complete |
| Product Images | Primary image display and gallery support | ✅ Complete |
| Stock Management | Real-time stock quantity tracking | ✅ Complete |
| Product Status | Active/Inactive product states | ✅ Complete |
| Product Ratings | User ratings and average rating system | ✅ Complete |
| Search Functionality | Full-text product search | ✅ Complete |

### 3. **Shopping Cart**
| Feature | Description | Status |
|---------|-------------|--------|
| Add to Cart | Add products with quantity selection | ✅ Complete |
| Cart Management | View, modify, and remove cart items | ✅ Complete |
| Quantity Updates | Adjust item quantities in cart | ✅ Complete |
| Cart Persistence | Session-based and user-based storage | ✅ Complete |
| Cart Totals | Automatic calculation of subtotal and total | ✅ Complete |
| Cart Validation | Stock availability validation before checkout | ✅ Complete |

### 4. **Checkout Process**
| Feature | Description | Status |
|---------|-------------|--------|
| Shipping Address | Collect and validate shipping addresses | ✅ Complete |
| Order Summary | Review items before final purchase | ✅ Complete |
| Payment Processing | Integration-ready for payment gateways | ✅ Complete |
| Order Creation | Create order records from cart | ✅ Complete |
| Order Confirmation | Confirmation page and email notifications | ✅ Complete |
| Multiple Payment Methods | Support for different payment options | ✅ Complete |

### 5. **Order Management**
| Feature | Description | Status |
|---------|-------------|--------|
| Order Tracking | View order status and history | ✅ Complete |
| Order History | Access to all past orders | ✅ Complete |
| Order Details | Comprehensive order information | ✅ Complete |
| Order Items | Individual item tracking within orders | ✅ Complete |
| Order Status Updates | Pending, Processing, Shipped, Delivered states | ✅ Complete |
| Order Cancellation | Cancel orders within allowed time window | ✅ Complete |

### 6. **Reviews & Ratings**
| Feature | Description | Status |
|---------|-------------|--------|
| Product Reviews | Customers can write detailed product reviews | ✅ Complete |
| Star Ratings | 1-5 star rating system | ✅ Complete |
| Review Moderation | Admin review approval workflow | ✅ Complete |
| Review Display | Show reviews on product pages | ✅ Complete |
| Average Rating | Calculate and display product ratings | ✅ Complete |
| Review Filtering | Sort reviews by helpful/recent | ✅ Complete |

### 7. **Wishlist**
| Feature | Description | Status |
|---------|-------------|--------|
| Add to Wishlist | Save favorite products for later | ✅ Complete |
| Wishlist Management | View and manage wishlist items | ✅ Complete |
| Move to Cart | Transfer wishlist items to shopping cart | ✅ Complete |
| Remove from Wishlist | Delete items from wishlist | ✅ Complete |
| Wishlist Sharing | Share wishlists with others | ✅ Complete |
| Multiple Wishlists | Support for multiple wishlist categories | ✅ Complete |

### 8. **Support & Communication**
| Feature | Description | Status |
|---------|-------------|--------|
| Contact Form | General inquiries submission | ✅ Complete |
| FAQ Section | Frequently asked questions and answers | ✅ Complete |
| Order Issues | Report problems with orders | ✅ Complete |
| Support Tickets | Ticket-based issue tracking system | ✅ Complete |
| Email Notifications | Automated notifications for order updates | ✅ Complete |
| Customer Support Dashboard | Admin support ticket management | ✅ Complete |

### 9. **Admin Management**
| Feature | Description | Status |
|---------|-------------|--------|
| Admin Dashboard | Overview of platform statistics | ✅ Complete |
| User Management | View and manage user accounts | ✅ Complete |
| Product Management | CRUD operations for products | ✅ Complete |
| Order Management | View and manage all orders | ✅ Complete |
| Review Moderation | Approve/reject customer reviews | ✅ Complete |
| Support Tickets | Manage customer support requests | ✅ Complete |
| Site Settings | Configure platform settings | ✅ Complete |

### 10. **Design System & UX**
| Feature | Description | Status |
|---------|-------------|--------|
| Modern UI Components | Buttons, cards, forms, modals | ✅ Complete |
| Responsive Design | Mobile, tablet, and desktop support | ✅ Complete |
| Dark Mode | Dark theme support across platform | ✅ Complete |
| Animations | Smooth transitions and micro-interactions | ✅ Complete |
| Accessibility | WCAG 2.1 AA compliance | ✅ Complete |
| Typography System | Poppins (headings) + Inter (body) | ✅ Complete |
| Color System | Teal accent with professional palette | ✅ Complete |

---

## 💻 Technology Stack

### Backend Framework
- **Symfony 5.4** - Modern PHP web application framework
- **PHP 7.2.5+** - Server-side programming language
- **Doctrine ORM** - Object-relational mapping for database
- **Doctrine Migrations** - Database version control

### Frontend Technologies
- **Twig** - Server-side templating engine
- **Stimulus.js** - Lightweight JavaScript framework
- **Webpack Encore** - Asset bundling and compilation
- **Babel** - JavaScript transpiler for modern features
- **Bootstrap Integration** - CSS framework components

### Database
- **MySQL** - Primary data storage via Doctrine ORM
- **Migrations** - 20+ migrations for schema management

### Development & Deployment
- **Docker Compose** - Containerized development environment
- **PHPUnit** - Unit testing framework
- **PHPStan** - Static analysis for PHP code
- **ESLint** - JavaScript linting

### Security & Authentication
- **Symfony Security Bundle** - Built-in authentication system
- **Password Hashing** - Secure password storage with Symfony
- **Email Verification** - SymfonyCasts Verify Email Bundle
- **CSRF Protection** - Cross-site request forgery prevention
- **Role-Based Access Control (RBAC)** - User permission system

### Additional Libraries
- **DomPDF** - PDF generation for invoices/documents
- **Mailer Bundle** - Email sending capabilities
- **Form Builder** - Symfony Forms for validation
- **Validator** - Data validation and constraints

---

## 🗂️ UML Architecture

### Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         CORE ENTITIES                                │
└─────────────────────────────────────────────────────────────────────┘

                              USER
                         ┌──────────┐
                         │ id (PK)  │
                         │ email*   │◄────────────┐
                         │ password │             │
                         │ roles    │             │
                         │ firstName│         1:N │ Orders
                         │ lastName │         N:1 │ Reviews
                         │ phone    │         N:M │ Wishlists
                         │ verified │         1:N │ Support Tickets
                         │ banned   │             │
                         │ createdAt│             │
                         └──────────┘             │
                                                  │
                    ┌─────────────────────────────┼──────────────┐
                    │                             │              │
                    ▼                             ▼              ▼
                ┌────────────┐            ┌─────────────┐    ┌──────────┐
                │   ORDER    │            │   REVIEW    │    │ WISHLIST │
                │ id (PK)    │            │ id (PK)     │    │ id (PK)  │
                │ user_id*   │            │ user_id*    │    │ user_id* │
                │ status     │            │ product_id* │    │product_id*
                │ total      │            │ rating      │    │ createdAt│
                │ createdAt  │            │ comment     │    └──────────┘
                └────────────┘            │ createdAt   │
                      │                   └─────────────┘
                      │ 1:N
                      └──────────────────────┐
                                            │
                                    ┌───────▼────────┐
                                    │  ORDER ITEM    │
                                    │ id (PK)        │
                                    │ order_id*      │
                                    │ product_id*    │
                                    │ quantity       │
                                    │ price          │
                                    └────────────────┘
                                            │
                                            │ N:1
                                            │
                                    ┌───────▼───────┐
                                    │    PRODUCT    │
                                    │ id (PK)       │
                                    │ category_id*  │
                                    │ name          │
                                    │ price         │
                                    │ description   │
                                    │ image         │
                                    │ stockQuantity │
                                    │ status        │
                                    │ rating        │
                                    │ createdAt     │
                                    └───────┬───────┘
                                            │ N:1
                                            │
                                    ┌───────▼─────────┐
                                    │   CATEGORY      │
                                    │ id (PK)         │
                                    │ name            │
                                    │ description     │
                                    │ image           │
                                    │ createdAt       │
                                    └─────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                    SUPPORT & ADDITIONAL ENTITIES                    │
└─────────────────────────────────────────────────────────────────────┘

   ┌──────────────┐      ┌──────────────┐      ┌────────────────┐
   │   CONTACT    │      │     FAQ      │      │ SUPPORT TICKET │
   │ id (PK)      │      │ id (PK)      │      │ id (PK)        │
   │ email        │      │ question     │      │ user_id*       │
   │ name         │      │ answer       │      │ title          │
   │ message      │      │ category     │      │ description    │
   │ createdAt    │      │ createdAt    │      │ status         │
   └──────────────┘      └──────────────┘      │ createdAt      │
                                                └────────────────┘

   ┌──────────────┐      ┌──────────────┐      ┌────────────────┐
   │ ORDER ISSUE  │      │   ADDRESS    │      │  PAYMENT METHOD│
   │ id (PK)      │      │ id (PK)      │      │ id (PK)        │
   │ order_id*    │      │ user_id*     │      │ user_id*       │
   │ description  │      │ street       │      │ cardType       │
   │ status       │      │ city         │      │ lastFourDigits │
   │ createdAt    │      │ postalCode   │      │ expiryDate     │
   └──────────────┘      │ country      │      │ isDefault      │
                         │ createdAt    │      │ createdAt      │
                         └──────────────┘      └────────────────┘

   ┌──────────────┐      ┌──────────────┐
   │   SETTINGS   │      │  CHANGELOG   │
   │ id (PK)      │      │ (Audit Trail)│
   │ siteTitle    │      │ entity       │
   │ description  │      │ action       │
   │ contactEmail │      │ userId       │
   │ updatedAt    │      │ timestamp    │
   └──────────────┘      └──────────────┘
```

### Class Diagram - Main Entities

```
┌──────────────────────────────────────────────────────────────┐
│                        User                                   │
├──────────────────────────────────────────────────────────────┤
│ - id: int (PK)                                               │
│ - email: string (UNIQUE)                                     │
│ - password: string (hashed)                                  │
│ - firstName: string                                          │
│ - lastName: string                                           │
│ - phone: string                                              │
│ - roles: array (JSON)                                        │
│ - isVerified: boolean                                        │
│ - banned: boolean                                            │
│ - resetToken: string                                         │
│ - resetTokenExpiresAt: DateTime                              │
│ - createdAt: DateTime                                        │
├──────────────────────────────────────────────────────────────┤
│ + getId(): int                                               │
│ + getEmail(): string                                         │
│ + setEmail(email): void                                      │
│ + getPassword(): string                                      │
│ + setPassword(hash): void                                    │
│ + getRoles(): array                                          │
│ + hasRole(role): boolean                                     │
│ + ban(): void                                                │
│ + unban(): void                                              │
│ + isBanned(): boolean                                        │
│ + isVerified(): boolean                                      │
│ + verify(): void                                             │
└──────────────────────────────────────────────────────────────┘
                              △
                              │ implements
                              │
            ┌─────────────────┴──────────────────┐
            │                                    │
        UserInterface          PasswordAuthenticatedUserInterface


┌──────────────────────────────────────────────────────────────┐
│                       Product                                 │
├──────────────────────────────────────────────────────────────┤
│ - id: int (PK)                                               │
│ - name: string                                               │
│ - description: text                                          │
│ - price: decimal(10,2)                                       │
│ - image: string (path)                                       │
│ - stockQuantity: int                                         │
│ - rating: decimal(3,2)                                       │
│ - status: boolean (active/inactive)                          │
│ - category: Category (FK)                                    │
│ - createdAt: DateTime                                        │
│ - reviews: Collection<Review>                                │
│ - wishlists: Collection<Wishlist>                            │
│ - orderItems: Collection<OrderItem>                          │
├──────────────────────────────────────────────────────────────┤
│ + getId(): int                                               │
│ + getName(): string                                          │
│ + setName(name): void                                        │
│ + getPrice(): float                                          │
│ + setPrice(price): void                                      │
│ + getStockQuantity(): int                                    │
│ + decreaseStock(quantity): void                              │
│ + hasStockAvailable(quantity): boolean                       │
│ + isActive(): boolean                                        │
│ + getRating(): float                                         │
│ + updateRating(): void                                       │
│ + addReview(review): void                                    │
└──────────────────────────────────────────────────────────────┘
                              △
                              │ ManyToOne
                              │
                         ┌────┴─────────┐
                         │              │
                      Category       Review


┌──────────────────────────────────────────────────────────────┐
│                        Order                                  │
├──────────────────────────────────────────────────────────────┤
│ - id: int (PK)                                               │
│ - user: User (FK)                                            │
│ - status: string (pending|processing|shipped|delivered)      │
│ - total: decimal(10,2)                                       │
│ - createdAt: DateTime                                        │
│ - orderItems: Collection<OrderItem>                          │
├──────────────────────────────────────────────────────────────┤
│ + getId(): int                                               │
│ + getUser(): User                                            │
│ + getStatus(): string                                        │
│ + setStatus(status): void                                    │
│ + getTotal(): float                                          │
│ + calculateTotal(): void                                     │
│ + addOrderItem(item): void                                   │
│ + removeOrderItem(item): void                                │
│ + getOrderItems(): Collection                                │
│ + cancel(): void                                             │
│ + isDelivered(): boolean                                     │
└──────────────────────────────────────────────────────────────┘
                              △
                              │ OneToMany
                              │
                         OrderItem


┌──────────────────────────────────────────────────────────────┐
│                      Review                                   │
├──────────────────────────────────────────────────────────────┤
│ - id: int (PK)                                               │
│ - product: Product (FK)                                      │
│ - user: User (FK)                                            │
│ - rating: int (1-5)                                          │
│ - comment: text                                              │
│ - createdAt: DateTime                                        │
├──────────────────────────────────────────────────────────────┤
│ + getId(): int                                               │
│ + getProduct(): Product                                      │
│ + getUser(): User                                            │
│ + getRating(): int                                           │
│ + setRating(rating): void                                    │
│ + getComment(): string                                       │
│ + setComment(comment): void                                  │
└──────────────────────────────────────────────────────────────┘
```

### State Diagrams

#### Order Status Flow
```
┌─────────────┐
│   PENDING   │ ◄─── Initial state after checkout
└──────┬──────┘
       │
       ▼
┌─────────────────┐
│  PROCESSING     │ ◄─── Admin confirms order
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│    SHIPPED      │ ◄─── Order sent to customer
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│   DELIVERED     │ ◄─── Order received
└─────────────────┘
```

#### User Account Flow
```
┌──────────────────┐
│  UNVERIFIED      │ ◄─── Initial registration
└──────┬───────────┘
       │ Email verified
       ▼
┌──────────────────┐
│    ACTIVE        │ ◄─── Normal user state
└──────┬───────────┘
       │ Admin bans user
       ▼
┌──────────────────┐
│    BANNED        │ ◄─── Access restricted
└──────────────────┘
```

### Use Case Diagram

```
                                    ┌─────────────────┐
                                    │     System      │
                                    │  2WHY Platform  │
                                    └─────────────────┘
                                            △
                  ┌─────────────┬───────────┼───────────┬─────────────┐
                  │             │           │           │             │
            ┌─────▼────┐  ┌────▼──────┐ ┌─▼────────┐ ┌▼───────────┐
            │  Browse   │  │  Shopping │ │  Order   │ │  Support  │
            │ Products  │  │   Cart    │ │Management│ │   System  │
            └──────────┘  └───────────┘ └──────────┘ └───────────┘
                  │             │           │             │
              ┌───┼─────────────┼───────────┼─────────────┼────┐
              │   │             │           │             │    │
         ┌────▼───┴─────┐      ┌┴──────────▼┐      ┌─────▼──┐ │
         │   CUSTOMER   │      │    ADMIN   │      │  GUEST │ │
         └──────────────┘      └────────────┘      └────────┘ │
                                                               │
         ◄──────────────── Use Cases ──────────────────────────┤
         │                                                     │
         ├─ Register/Login                                    │
         ├─ Browse Products & Categories                      │
         ├─ Search Products                                   │
         ├─ View Product Details                              │
         ├─ Add to Cart/Wishlist                              │
         ├─ Checkout                                          │
         ├─ Track Orders                                      │
         ├─ Write Reviews                                     │
         ├─ Submit Support Tickets                            │
         ├─ Manage Profile                                    │
         │                                                     │
         │ [ADMIN ONLY]                                       │
         ├─ Manage Products                                   │
         ├─ Manage Orders                                     │
         ├─ Approve Reviews                                   │
         ├─ Manage Support Tickets                            │
         ├─ Manage Users                                      │
         └─ View Analytics                                    │
```

### Sequence Diagrams

#### 1. User Registration Sequence Diagram

```
Customer    │RegistrationCtrl │ UserRepository │ MailerService │ Database
   │        │        │         │                │               │
   │─ Fills Form ────▶│        │                │               │
   │        │        │        │                │               │
   │        │─ Validate Input ─▶│               │               │
   │        │◀─────────────────│               │               │
   │        │                  │                │               │
   │        │─ Hash Password ──▶│               │               │
   │        │◀─────────────────│               │               │
   │        │                  │                │               │
   │        │──────────────────────────────────▶│ User Created  │
   │        │                  │                │               │
   │        │                  │                │──────────────▶│
   │        │                  │                │               │
   │        │─ Generate Token ─▶│               │               │
   │        │◀─────────────────│               │               │
   │        │                  │                │               │
   │        │─ Create Verification Email ─────▶│               │
   │        │                  │                │               │
   │        │◀────────────────────────────────│               │
   │        │                  │                │               │
   │◀─ Confirmation Page ─────│                │               │
   │        │                  │                │               │
   │─ Click Email Link ──────▶│        │ Verify Email  │
   │        │                  │                │               │
   │        │─ Update Status ──────────────────▶│               │
   │        │                  │                │               │
   │        │                  │                │──────────────▶│
   │        │                  │                │               │
   │◀─ Account Verified ──────│                │               │
```

#### 2. Product Purchase & Checkout Sequence Diagram

```
Customer    │CartController │ProductRepo │OrderController │PaymentGateway │Database
   │        │       │        │            │       │        │                │
   │─ Add to Cart ──▶│       │            │       │        │                │
   │        │       │─ Verify Stock ─────▶│       │        │                │
   │        │       │◀──────────────────│        │        │                │
   │        │       │                    │       │        │                │
   │        │       │─ Add Item ────────────────────────────────────────────▶│
   │        │       │                    │       │        │                │
   │◀─ Cart Updated ─│       │            │       │        │                │
   │        │       │        │            │       │        │                │
   │─ Proceed to Checkout ──▶│     │ Review Items│       │        │
   │        │       │        │    │       │        │        │                │
   │─ Enter Shipping Info ──▶│    │       │        │        │                │
   │        │       │        │    │       │        │        │                │
   │─ Select Payment Method ▶│    │       │        │        │                │
   │        │       │        │    │       │        │        │                │
   │─ Confirm Order ─────────────────────────────▶│       │                │
   │        │       │        │    │       │        │        │                │
   │        │       │        │    │       │─ Process Payment ▶│               │
   │        │       │        │    │       │        │◀──────────────────────│
   │        │       │        │    │       │        │ Payment Confirmed    │
   │        │       │        │    │       │        │                     │
   │        │       │────────────────────────────────────────────────────▶│
   │        │       │        │    │       │        │    Order Created    │
   │        │       │        │    │       │        │                     │
   │        │       │─ Reduce Stock ────────────────────────────────────▶│
   │        │       │                    │       │        │                │
   │        │       │─ Clear Cart ───────────────────────────────────────▶│
   │        │       │                    │       │        │                │
   │◀─ Order Confirmation Page ──────────│       │        │                │
   │        │       │        │    │       │        │        │                │
   │◀─ Confirmation Email Sent ─────────│       │        │                │
```

#### 3. Review Submission & Moderation Sequence Diagram

```
Customer    │ReviewController │Database │Admin │EmailService │Product
   │        │       │          │        │      │             │
   │─ Read Product ────────────────────────────────────────────▶│
   │        │       │          │        │      │             │
   │─ Click Write Review ─────▶│        │      │             │
   │        │       │          │        │      │             │
   │─ Submit Review Form ─────▶│        │      │             │
   │        │       │          │        │      │             │
   │        │─ Validate Input ─▶│       │      │             │
   │        │       │◀─────────│        │      │             │
   │        │       │          │        │      │             │
   │        │─ Save Review (Pending) ─▶│      │             │
   │        │       │          │        │      │             │
   │◀─ Thank You Message ─────│        │      │             │
   │        │       │          │        │      │             │
   │        │─ Notify Admin ──────────────────────────────────▶│
   │        │       │          │        │      │             │
   │        │       │          │        │      │             │
   │─ [ADMIN DASHBOARD] ──────────────────────────▶│          │
   │        │       │          │        │ Review Pending    │
   │        │       │          │        │      │             │
   │        │       │          │        │─ Approve/Reject ──▶│
   │        │       │          │        │      │             │
   │        │       │          │ Update Review ◀─│             │
   │        │       │          │ Status: APPROVED            │
   │        │       │          │        │      │             │
   │        │       │          │        │─ Send Notification ▶│
   │        │       │          │        │      │             │
   │        │       │          │─ Recalculate Rating ────────▶│
   │        │       │          │        │      │             │
   │        │       │          │        │      │ Update Product
   │        │       │          │        │      │ (New Rating)
   │◀─ Email: Review Published ───────────────│             │
```

#### 4. Order Tracking & Support Ticket Sequence Diagram

```
Customer    │ProfileController │OrderRepository │SupportTicketCtrl │Admin │Database
   │        │       │           │                │       │        │      │
   │─ View Orders ──▶│           │                │       │        │      │
   │        │       │─ Get User Orders ─────────▶│       │        │      │
   │        │       │           │◀───────────────│       │        │      │
   │        │       │           │ [List Orders]  │       │        │      │
   │        │       │           │                │       │        │      │
   │◀─ Order History Page ─────│           │       │        │      │
   │        │       │           │                │       │        │      │
   │─ Click on Order ──────────▶│           │       │        │      │
   │        │       │           │                │       │        │      │
   │        │       │─ Get Order Details ───────▶│       │        │      │
   │        │       │           │◀───────────────│       │        │      │
   │        │       │           │ [Order Data]   │       │        │      │
   │        │       │           │                │       │        │      │
   │◀─ Order Detail Page ──────│           │       │        │      │
   │        │       │           │ Status: SHIPPED│       │        │      │
   │        │       │           │                │       │        │      │
   │─ Report Issue ─────────────────────────────▶│       │        │      │
   │        │       │           │                │ Issue Form     │      │
   │        │       │           │                │        │      │      │
   │─ Submit Ticket ───────────────────────────▶│        │      │      │
   │        │       │           │                │─ Validate ───▶│      │
   │        │       │           │                │        │◀─────│      │
   │        │       │           │                │        │      │      │
   │        │       │           │                │──────────────────────▶│
   │        │       │           │                │        │      │ Ticket Created
   │        │       │           │                │        │      │
   │◀─ Ticket Confirmation ────────────────────│        │      │
   │        │       │           │                │        │      │
   │        │       │           │ [ADMIN VIEW]   │        │      │
   │        │       │           │                │        │─ New Ticket ──▶│
   │        │       │           │                │        │      │ in Queue
   │        │       │           │                │        │      │
   │        │       │           │                │        │─ Review & Reply
   │        │       │           │                │        │      │
   │        │       │           │                │─ Update Status ──────▶│
   │        │       │           │                │        │      │ IN_PROGRESS
   │        │       │           │                │        │      │
   │◀─ Email: Support Response ───────────────│        │      │
```

#### 5. Admin Product Management Sequence Diagram

```
Admin       │AdminController │ProductCtrl │Database │ImageService │Cache
  │         │       │         │            │         │             │
  │─ Login as Admin ──────────────────────────────────────────────▶│
  │         │       │         │            │         │             │
  │─ Go to Product Management ▶│  │         │         │             │
  │         │       │         │ Dashboard  │         │             │
  │         │       │         │            │         │             │
  │◀─ Product List Page ──────│  │         │         │             │
  │         │       │         │            │         │             │
  │─ Add New Product ─────────▶│  │         │         │             │
  │         │       │         │ Form       │         │             │
  │         │       │         │            │         │             │
  │─ Fill Product Form ───────▶│  │         │         │             │
  │   (name, price, image)     │  │         │         │             │
  │         │       │         │            │         │             │
  │─ Upload Image ────────────────────────────────────▶│             │
  │         │       │         │            │         │ Store Image │
  │         │       │         │            │ Image Path            │
  │         │       │         │            │◀────────│             │
  │         │       │         │            │         │             │
  │─ Submit Form ─────────────▶│  │         │         │             │
  │         │       │         │            │         │             │
  │         │─ Validate Data ─▶│  │         │         │             │
  │         │       │◀─────────│  │         │         │             │
  │         │       │         │            │         │             │
  │         │─────────────────────────────────────────────────────▶│
  │         │       │         │   Create Product Record            │
  │         │       │         │            │         │             │
  │◀─ Product Created Successfully ──────│  │         │             │
  │         │       │         │            │         │             │
  │─ Edit Product ────────────▶│  │         │         │             │
  │         │       │         │ Details    │         │             │
  │         │       │         │            │         │             │
  │─ Update Price ────────────▶│  │         │         │             │
  │         │       │         │            │         │             │
  │─ Submit Changes ──────────────────────────────────────────────▶│
  │         │       │         │   Update Product Record            │
  │         │       │         │            │         │             │
  │         │       │         │            │─ Clear Cache ────────▶│
  │         │       │         │            │         │ Cache Reset │
  │         │       │         │            │         │             │
  │◀─ Changes Saved Successfully ──────────────────────────────────│
```

#### 6. Shopping Cart to Wishlist Interaction Sequence Diagram

```
Customer    │CartController │WishlistCtrl │ProductCtrl │Database
   │        │       │        │      │       │            │
   │─ Browse Products ─────────────────────────────────▶│
   │        │       │        │      │       │            │
   │─ Add to Cart ──────────▶│      │       │            │
   │        │       │        │      │       │            │
   │        │─ Add Item ─────────────────────────────────▶│
   │        │       │        │      │       │            │
   │◀─ Item Added ──────────│      │       │            │
   │        │       │        │      │       │            │
   │─ Also Add to Wishlist ──────────▶│    │            │
   │        │       │        │      │       │            │
   │        │       │        │─ Check Duplicate ────────▶│
   │        │       │        │      │       │            │
   │        │       │        │      │◀──── Not in Wishlist
   │        │       │        │      │       │            │
   │        │       │        │─ Add to Wishlist ────────▶│
   │        │       │        │      │       │            │
   │◀─ Item Added to Wishlist ──────│      │            │
   │        │       │        │      │       │            │
   │─ View Cart ────────────▶│      │       │            │
   │        │       │        │      │       │            │
   │        │─ Get Cart Items ─────────────────────────▶│
   │        │       │        │      │       │            │
   │◀─ Cart Page (2 items) ─│      │       │            │
   │        │       │        │      │       │            │
   │─ Remove 1 Item from Cart ────▶│      │            │
   │        │       │        │      │       │            │
   │        │─ Remove Item ──────────────────────────────▶│
   │        │       │        │      │       │            │
   │◀─ Item Removed ────────│      │       │            │
   │        │       │        │      │       │            │
   │─ View Wishlist ───────────────▶│     │            │
   │        │       │        │      │─ Get Wishlist Items ▶
   │        │       │        │      │       │            │
   │◀─ Wishlist (1 item) ──────────│     │            │
   │        │       │        │      │       │            │
   │─ Move Wishlist Item to Cart ──────────────────────▶│
   │        │       │        │      │       │            │
   │        │─ Add to Cart ──────────────────────────────▶│
   │        │       │        │      │       │            │
   │        │─ Remove from Wishlist ──────────────────────▶
   │        │       │        │      │       │            │
   │◀─ Success ─────────────│      │       │            │
```

---

## 📊 Database Schema

### Key Tables & Relationships

| Table | Purpose | Key Relationships |
|-------|---------|------------------|
| `user` | Store user accounts | 1:N Orders, 1:N Reviews, 1:N Wishlists, 1:N Support Tickets |
| `product` | Product catalog | N:1 Category, 1:N Reviews, 1:N OrderItems, 1:N Wishlists |
| `category` | Product categorization | 1:N Products |
| `order` | Customer orders | N:1 User, 1:N OrderItems |
| `order_item` | Individual items in orders | N:1 Order, N:1 Product |
| `review` | Product reviews | N:1 Product, N:1 User |
| `wishlist` | Saved products | N:1 User, N:1 Product |
| `address` | Shipping addresses | N:1 User |
| `payment_method` | Payment details | N:1 User |
| `support_ticket` | Customer support issues | N:1 User |
| `order_issue` | Order-specific problems | N:1 Order |
| `contact` | General inquiries | - |
| `faq` | FAQ entries | - |
| `settings` | Site configuration | - |

### Migration History (20+ Migrations)
- User authentication schema
- Product catalog tables
- Order management system
- Review system implementation
- Support ticket system
- Wishlist functionality
- Address and payment methods
- Settings and configuration

---

## 👥 User Workflows

### 1. Customer Registration & Login Flow
```
START
  │
  ├─► Visit Registration Page
  │      │
  │      └─► Fill Registration Form
  │             (email, password, name, phone)
  │             │
  │             ├─ Validate Input
  │             └─ Hash Password
  │                   │
  │                   ▼
  │      User Created in Database
  │             │
  │             └─► Send Verification Email
  │                   │
  │                   ▼
  │      Click Email Link
  │             │
  │             └─► Account Verified
  │
  ├─► Login Page
  │      │
  │      └─► Enter Email & Password
  │             │
  │             ├─ Verify Credentials
  │             └─ Check if Banned
  │                   │
  │                   ▼
  │      Session Created
  │             │
  │             └─► Redirect to Dashboard
  │
  └─ END (Logged In)
```

### 2. Shopping Cart & Checkout Flow
```
START (Viewing Product)
  │
  ├─► Add to Cart
  │      │
  │      ├─ Check Stock Availability
  │      └─ Add to Session/Database
  │             │
  │             ▼
  │      Product in Cart
  │
  ├─► Update Quantity / Remove Items
  │
  ├─► Proceed to Checkout
  │      │
  │      ├─ Review Cart Items
  │      └─ Enter Shipping Address
  │             │
  │             ├─ Select Payment Method
  │             └─ Add Billing Info
  │                   │
  │                   ▼
  │      Review Order Summary
  │             │
  │             ├─► Confirm & Process Payment
  │             │      │
  │             │      ├─ Validate Payment
  │             │      └─ Create Order Record
  │             │             │
  │             │             ├─ Reduce Stock
  │             │             └─ Clear Cart
  │             │
  │             └─► Payment Successful
  │                   │
  │                   ▼
  │      Order Confirmation Page
  │             │
  │             └─► Send Confirmation Email
  │
  └─ END (Order Complete)
```

### 3. Product Review Flow
```
START (Order Received)
  │
  ├─► Wait for Delivery
  │      │
  │      └─► Order Status: DELIVERED
  │             │
  │             ▼
  │      Browse Product Page
  │             │
  │             └─► Click "Write Review"
  │                   │
  │                   ├─ Select Star Rating (1-5)
  │                   └─ Write Review Comment
  │                         │
  │                         ▼
  │      Submit Review
  │             │
  │             ├─ Save to Database
  │             └─ Send to Moderation Queue
  │                   │
  │                   ▼
  │      Admin Reviews Content
  │             │
  │             ├─ Approve ──────┐
  │             │                │
  │             └─ Reject ────┐  │
  │                           │  │
  │                       [Remove]
  │                           │
  │                           ▼
  │      Review Published
  │             │
  │             └─► Update Product Rating
  │
  └─ END
```

### 4. Admin Support Ticket Management
```
START (Customer Support Request)
  │
  ├─► Customer Submits Ticket
  │      │
  │      └─► Select Category
  │             (Order Issues, Product Question, etc.)
  │             │
  │             ├─ Enter Description
  │             └─ Attach Files (optional)
  │                   │
  │                   ▼
  │      Ticket Created (Status: OPEN)
  │             │
  │             ├─► Send Confirmation Email
  │             │
  │             └─► Assign Ticket Number
  │
  ├─► Admin Views Dashboard
  │      │
  │      ├─ See New Tickets
  │      └─ Assign to Team Member
  │             │
  │             ▼
  │      Support Agent Reviews Ticket
  │             │
  │             ├─ Investigate Issue
  │             └─ Reply with Solution
  │                   │
  │                   ▼
  │      Update Ticket (Status: IN_PROGRESS)
  │             │
  │             └─► Send Reply Email
  │
  ├─► Customer Receives Reply
  │      │
  │      ├─ Reads Solution
  │      └─ Mark as Resolved (or Reply)
  │             │
  │             ▼
  │      Ticket Closed (Status: CLOSED)
  │             │
  │             └─► Archive Ticket
  │
  └─ END (Issue Resolved)
```

---

## ✅ Implementation Status

### Phase 1: Core Platform (100% Complete)
- ✅ User authentication & management
- ✅ Product catalog system
- ✅ Shopping cart functionality
- ✅ Checkout process
- ✅ Order management
- ✅ Basic review system

### Phase 2: Advanced Features (100% Complete)
- ✅ Wishlist functionality
- ✅ Review moderation
- ✅ Support ticket system
- ✅ Admin dashboard
- ✅ FAQ management
- ✅ Contact form

### Phase 3: Design & UX (100% Complete)
- ✅ Modern visual identity
- ✅ Responsive design
- ✅ Dark mode support
- ✅ Accessibility (WCAG 2.1 AA)
- ✅ Component library
- ✅ Animation system
- ✅ Brand guidelines

### Phase 4: Testing & Optimization (In Progress)
- ✅ Unit tests
- ✅ Route testing
- ✅ Performance optimization
- 🔄 Integration tests
- 🔄 E2E testing
- 🔄 Security audits

---

## 🎨 Design System

### Color Palette
- **Primary**: Deep Slate (`#1a365d`) - Trust & Professionalism
- **Accent**: Teal (`#06b6d4`) - Innovation & Energy
- **Semantic Colors**: Success (`#10b981`), Error (`#ef4444`), Warning (`#f59e0b`), Info (`#3b82f6`)

### Typography
- **Headings**: Poppins (Bold)
- **Body**: Inter (Regular)
- **Responsive**: 12px - 48px scale

### Component Library
- **Buttons**: 8+ variations (Primary, Secondary, Outline, Ghost, etc.)
- **Cards**: Standard, Featured, Minimal, Interactive
- **Forms**: Enhanced inputs, selects, textareas with validation
- **Modals**: Dialog components with animations
- **Navigation**: Responsive menus with indicators
- **Icons**: 50+ professionally designed icons

---

## 📞 Quick Reference

### Main Routes
- `/` - Home page
- `/shop` - Product catalog
- `/product/{id}` - Product details
- `/cart` - Shopping cart
- `/checkout` - Checkout process
- `/orders` - Order history
- `/profile` - User profile
- `/dashboard` - User dashboard
- `/admin` - Admin panel
- `/faq` - FAQ page
- `/support` - Support system
- `/contact` - Contact form

### Key Files
- **Controllers**: `src/Controller/`
- **Entities**: `src/Entity/`
- **Templates**: `templates/`
- **Styles**: `assets/styles/`
- **Documentation**: Root directory `*.md` files

### Database Connection
- **Type**: MySQL
- **Engine**: Doctrine ORM
- **Migrations**: `migrations/` directory

---

## 🚀 Getting Started

### Installation
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# Build assets
npm run build
```

### Running the Application
```bash
# Development server
php bin/console server:start 127.0.0.1:8000

# Or with WAMP/LAMP
# Access via http://localhost/2why
```

### Testing
```bash
# Run tests
php bin/phpunit
```

---

## 📝 Notes

This is a comprehensive, production-ready e-commerce platform with:
- Modern technology stack
- Clean architecture
- Extensive design system
- Complete feature set
- Professional branding
- Accessibility compliance

---

**Last Updated**: November 30, 2025
**Project Status**: Complete & Functional
**Version**: 1.0.0
