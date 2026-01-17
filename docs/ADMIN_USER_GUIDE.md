# Admin User Guide - IlyassFit

This guide provides step-by-step instructions for managing the content and features of the IlyassFit website via the administration panel.

## 1. Accessing the Admin Panel

**URL**: `http://your-website.com/admin` (or `http://localhost/IlyassFit/admin` for local testing)

1.  Navigate to the admin URL.
2.  Enter your **Username** and **Password**.
3.  Click **Sign In**.

> **Note**: For security, multiple failed login attempts will temporarily lock the login form.

---

## 2. Dashboard Overview

After logging in, you will see the Dashboard, which gives a quick overview of your site's activity:
-   **Statistics**: Total counts for Messages, Reviews, and Gallery items.
-   **Unseen Messages**: A list of recent contact form submissions that haven't been opened yet.
-   **Quick Actions**: Click "View Details" on a message to mark it as seen and read the full content.

---

## 3. Managing Pricing Plans

Go to the **"Manage Pricing"** page to add, edit, or remove coaching packages.

### Adding a New Plan
1.  Fill in the **Plan Name** (e.g., "Gold Tier").
2.  Select **Coaching Type**:
    -   *Face-to-Face*: Appears in the "In-Person" section.
    -   *Online*: Appears in the "Online Coaching" section.
3.  Enter the **Price** (in DH) and **Duration** (e.g., "per month").
4.  **Booking URL**: Where the button redirects (default is `contact.php`).
5.  **Button Text**: Label for the action button (e.g., "Get Started").
6.  **Description**: A short summary of the plan.
7.  **Features**: Enter one feature per line. These will appear as bullet points on the card.
    *   *Example*:
        ```
        Custom Meal Plan
        24/7 Support
        Weekly Check-ins
        ```
8.  **Display Order**: Controls the sorting (lower numbers appear first).
9.  Click **Add Plan**.

### Editing or Deleting
-   **Edit**: Click the <i class="fas fa-edit"></i> (pencil icon) on a pricing card to load its data into the form. Update the fields and click "Update Plan".
-   **Delete**: Click the <i class="fas fa-trash"></i> (trash icon) to remove the plan permanently.
-   **Active Status**: Use the checkbox in Edit mode to toggle a plan's visibility without deleting it.

---

## 4. Managing Gallery

Go to the **"Manage Gallery"** page to control images displayed in the public gallery.

### Uploading Images
-   **Single Upload**: Select one image and click "Upload Image".
-   **Multiple Upload**: Select multiple files (hold Ctrl/Cmd) and click "Upload Multiple Images".

### Managing Images
-   **Delete**: Click the "Delete" button overlay on any image in the grid to remove it from the gallery and the server.
-   **ID**: Each image has a unique ID used for database reference.

---

## 5. Managing Reviews (Transformations)

Go to the **"Manage Reviews"** page to showcase client success stories and testimonials.

### Adding a Review
1.  **Client Name**: Name of the client.
2.  **Before Photo**: Upload the "Before" transformation picture.
3.  **After Photo**: Upload the "After" transformation picture.
4.  **Review Text**: The client's testimonial.
5.  Click **Add Review**.

> **Tip**: These reviews appear in the "About Me" carousel and the "Transformations" section on the homepage.

### Editing
-   Click the Edit icon to modify the text or replace photos.
-   Leaving a photo field empty during editing will keep the existing photo.

---

## 6. Managing Messages

Go to the **"Manage Messages"** page to view inquiries sent via the "Contact" page.

-   **Inbox**: View all messages with their status (Seen/Not Seen).
-   **Details**: Shows the Sender Name, Email, Phone, and Message body.
-   **Status**: Messages are marked as "Seen" automatically when viewed from the Dashboard or manually managed here.
-   **Delete**: Remove old messages to keep your inbox clean.

---

## 7. Security & Best Practices

-   **Logout**: Always use the **Logout** button in the navbar when finishing your session.
-   **Images**: Optimize images before uploading (keep them under 2MB) to ensure fast loading times for your visitors.
-   **Passwords**: If you need to change your admin password, please contact the developer or use the database management tool provided by your hosting.
