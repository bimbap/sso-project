#!/usr/bin/env python3
"""
Desktop Application Example for Laravel SSO Integration

This is a simple Python desktop application that demonstrates
how to integrate with the Laravel SSO web application using APIs.

Requirements:
- requests
- tkinter (usually comes with Python)
- webbrowser (built-in)

Install dependencies:
pip install requests
"""

import json
import tkinter as tk
from tkinter import ttk, messagebox, scrolledtext
import webbrowser
import requests
from urllib.parse import parse_qs, urlparse
import threading
import time

class LaravelSSOClient:
    def __init__(self, base_url, google_client_id):
        self.base_url = base_url.rstrip('/')
        self.google_client_id = google_client_id
        self.token = None
        self.user = None

    def get_google_auth_url(self):
        """Generate Google OAuth URL for desktop authentication"""
        return (
            f"https://accounts.google.com/o/oauth2/auth?"
            f"client_id={self.google_client_id}&"
            f"redirect_uri=urn:ietf:wg:oauth:2.0:oob&"
            f"scope=openid email profile&"
            f"response_type=code"
        )

    def login_with_code(self, auth_code):
        """Exchange authorization code for access token and login"""
        try:
            # Exchange code for access token (this would normally be done more securely)
            # For demo purposes, we'll simulate this process

            # In a real app, you'd exchange the code for an access token with Google
            # Then use that access token to call your Laravel API

            # For demo, we'll create a mock access token request
            response = requests.post(
                f"{self.base_url}/api/auth/google",
                json={"access_token": "demo_token_" + auth_code[:10]},
                timeout=10
            )

            if response.status_code == 200:
                data = response.json()
                if data.get('success'):
                    self.token = data['token']
                    self.user = data['user']
                    return True

            return False

        except requests.RequestException as e:
            print(f"Login error: {e}")
            return False

    def get_headers(self):
        """Get headers with authentication token"""
        if not self.token:
            raise Exception("Not authenticated. Please login first.")

        return {
            'Authorization': f'Bearer {self.token}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }

    def get_posts(self):
        """Fetch all posts"""
        try:
            response = requests.get(
                f"{self.base_url}/api/posts",
                headers=self.get_headers(),
                timeout=10
            )
            response.raise_for_status()
            return response.json()
        except requests.RequestException as e:
            raise Exception(f"Error fetching posts: {e}")

    def create_post(self, title, content, status='published'):
        """Create a new post"""
        try:
            response = requests.post(
                f"{self.base_url}/api/posts",
                json={
                    'title': title,
                    'content': content,
                    'status': status
                },
                headers=self.get_headers(),
                timeout=10
            )
            response.raise_for_status()
            return response.json()
        except requests.RequestException as e:
            raise Exception(f"Error creating post: {e}")

    def sync_posts(self, last_timestamp=None):
        """Sync posts with server"""
        try:
            if last_timestamp:
                url = f"{self.base_url}/api/sync/posts/changes/{last_timestamp}"
            else:
                url = f"{self.base_url}/api/sync/posts"

            response = requests.get(url, headers=self.get_headers(), timeout=10)
            response.raise_for_status()
            return response.json()
        except requests.RequestException as e:
            raise Exception(f"Error syncing posts: {e}")

    def logout(self):
        """Logout and invalidate token"""
        try:
            if self.token:
                requests.post(
                    f"{self.base_url}/api/logout",
                    headers=self.get_headers(),
                    timeout=5
                )
        except requests.RequestException:
            pass  # Ignore logout errors
        finally:
            self.token = None
            self.user = None

class DesktopApp:
    def __init__(self):
        self.client = LaravelSSOClient(
            base_url="http://localhost:8000",
            google_client_id="your-google-client-id-here"
        )

        self.root = tk.Tk()
        self.root.title("Laravel SSO Desktop Client")
        self.root.geometry("800x600")

        self.setup_ui()
        self.update_ui_state()

    def setup_ui(self):
        """Setup the user interface"""
        # Main notebook for tabs
        self.notebook = ttk.Notebook(self.root)
        self.notebook.pack(fill=tk.BOTH, expand=True, padx=10, pady=10)

        # Login tab
        self.login_frame = ttk.Frame(self.notebook)
        self.notebook.add(self.login_frame, text="Login")
        self.setup_login_tab()

        # Posts tab
        self.posts_frame = ttk.Frame(self.notebook)
        self.notebook.add(self.posts_frame, text="Posts")
        self.setup_posts_tab()

        # Create Post tab
        self.create_frame = ttk.Frame(self.notebook)
        self.notebook.add(self.create_frame, text="Create Post")
        self.setup_create_tab()

        # Sync tab
        self.sync_frame = ttk.Frame(self.notebook)
        self.notebook.add(self.sync_frame, text="Sync")
        self.setup_sync_tab()

    def setup_login_tab(self):
        """Setup login interface"""
        ttk.Label(self.login_frame, text="Laravel SSO Desktop Client",
                 font=('Arial', 16, 'bold')).pack(pady=20)

        ttk.Label(self.login_frame, text="To login, click the button below to open Google OAuth in your browser.").pack(pady=10)

        ttk.Button(self.login_frame, text="Login with Google",
                  command=self.start_google_auth).pack(pady=10)

        ttk.Label(self.login_frame, text="After authorization, paste the code here:").pack(pady=(20, 5))

        self.auth_code_var = tk.StringVar()
        ttk.Entry(self.login_frame, textvariable=self.auth_code_var, width=50).pack(pady=5)

        ttk.Button(self.login_frame, text="Complete Login",
                  command=self.complete_login).pack(pady=10)

        # User info display
        self.user_info_frame = ttk.Frame(self.login_frame)
        self.user_info_frame.pack(pady=20, fill=tk.X)

        self.user_info_text = scrolledtext.ScrolledText(self.user_info_frame, height=10, width=70)
        self.user_info_text.pack()

        ttk.Button(self.login_frame, text="Logout", command=self.logout).pack(pady=10)

    def setup_posts_tab(self):
        """Setup posts viewing interface"""
        ttk.Label(self.posts_frame, text="Posts", font=('Arial', 14, 'bold')).pack(pady=10)

        ttk.Button(self.posts_frame, text="Refresh Posts", command=self.load_posts).pack(pady=5)

        # Posts listbox with scrollbar
        posts_frame = ttk.Frame(self.posts_frame)
        posts_frame.pack(fill=tk.BOTH, expand=True, pady=10)

        self.posts_listbox = tk.Listbox(posts_frame)
        scrollbar = ttk.Scrollbar(posts_frame, orient=tk.VERTICAL, command=self.posts_listbox.yview)
        self.posts_listbox.configure(yscrollcommand=scrollbar.set)

        self.posts_listbox.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        scrollbar.pack(side=tk.RIGHT, fill=tk.Y)

        # Post details
        self.post_detail_text = scrolledtext.ScrolledText(self.posts_frame, height=10)
        self.post_detail_text.pack(fill=tk.X, pady=10)

        self.posts_listbox.bind('<<ListboxSelect>>', self.on_post_select)

    def setup_create_tab(self):
        """Setup post creation interface"""
        ttk.Label(self.create_frame, text="Create New Post", font=('Arial', 14, 'bold')).pack(pady=10)

        # Title
        ttk.Label(self.create_frame, text="Title:").pack(anchor=tk.W, padx=20)
        self.title_var = tk.StringVar()
        ttk.Entry(self.create_frame, textvariable=self.title_var, width=70).pack(pady=5, padx=20)

        # Content
        ttk.Label(self.create_frame, text="Content:").pack(anchor=tk.W, padx=20, pady=(10, 0))
        self.content_text = scrolledtext.ScrolledText(self.create_frame, height=15, width=70)
        self.content_text.pack(pady=5, padx=20)

        # Status
        ttk.Label(self.create_frame, text="Status:").pack(anchor=tk.W, padx=20, pady=(10, 0))
        self.status_var = tk.StringVar(value="published")
        status_combo = ttk.Combobox(self.create_frame, textvariable=self.status_var,
                                   values=["draft", "published", "archived"])
        status_combo.pack(pady=5, padx=20, anchor=tk.W)

        ttk.Button(self.create_frame, text="Create Post", command=self.create_post).pack(pady=20)

    def setup_sync_tab(self):
        """Setup synchronization interface"""
        ttk.Label(self.sync_frame, text="Data Synchronization", font=('Arial', 14, 'bold')).pack(pady=10)

        ttk.Button(self.sync_frame, text="Full Sync", command=self.full_sync).pack(pady=5)
        ttk.Button(self.sync_frame, text="Incremental Sync", command=self.incremental_sync).pack(pady=5)

        self.sync_status_text = scrolledtext.ScrolledText(self.sync_frame, height=20, width=70)
        self.sync_status_text.pack(pady=10, fill=tk.BOTH, expand=True)

        self.last_sync_timestamp = None

    def start_google_auth(self):
        """Open Google OAuth URL in browser"""
        auth_url = self.client.get_google_auth_url()
        webbrowser.open(auth_url)
        messagebox.showinfo("Authentication",
                           "Your browser will open for Google authentication. "
                           "After authorizing, copy the authorization code and paste it in the field below.")

    def complete_login(self):
        """Complete login with authorization code"""
        auth_code = self.auth_code_var.get().strip()
        if not auth_code:
            messagebox.showerror("Error", "Please enter the authorization code.")
            return

        try:
            if self.client.login_with_code(auth_code):
                messagebox.showinfo("Success", "Login successful!")
                self.update_ui_state()
                self.display_user_info()
            else:
                messagebox.showerror("Error", "Login failed. Please try again.")
        except Exception as e:
            messagebox.showerror("Error", f"Login error: {e}")

    def logout(self):
        """Logout user"""
        self.client.logout()
        self.update_ui_state()
        messagebox.showinfo("Success", "Logged out successfully.")

    def update_ui_state(self):
        """Update UI based on authentication state"""
        is_authenticated = self.client.token is not None

        # Enable/disable tabs based on auth state
        if is_authenticated:
            self.notebook.tab(1, state="normal")  # Posts
            self.notebook.tab(2, state="normal")  # Create
            self.notebook.tab(3, state="normal")  # Sync
        else:
            self.notebook.tab(1, state="disabled")
            self.notebook.tab(2, state="disabled")
            self.notebook.tab(3, state="disabled")
            # Clear data
            self.posts_listbox.delete(0, tk.END)
            self.post_detail_text.delete(1.0, tk.END)
            self.user_info_text.delete(1.0, tk.END)

    def display_user_info(self):
        """Display user information"""
        if self.client.user:
            info = json.dumps(self.client.user, indent=2)
            self.user_info_text.delete(1.0, tk.END)
            self.user_info_text.insert(1.0, f"Logged in as:\n{info}")

    def load_posts(self):
        """Load posts from server"""
        try:
            posts_data = self.client.get_posts()
            posts = posts_data.get('data', []) if isinstance(posts_data, dict) else posts_data

            self.posts_listbox.delete(0, tk.END)
            self.posts_data = posts

            for post in posts:
                title = post.get('title', 'Untitled')
                author = post.get('user', {}).get('name', 'Unknown')
                status = post.get('status', 'unknown')
                self.posts_listbox.insert(tk.END, f"[{status}] {title} - by {author}")

            if not posts:
                self.posts_listbox.insert(tk.END, "No posts available")

        except Exception as e:
            messagebox.showerror("Error", f"Failed to load posts: {e}")

    def on_post_select(self, event):
        """Handle post selection"""
        selection = self.posts_listbox.curselection()
        if selection and hasattr(self, 'posts_data'):
            index = selection[0]
            if index < len(self.posts_data):
                post = self.posts_data[index]
                details = json.dumps(post, indent=2)
                self.post_detail_text.delete(1.0, tk.END)
                self.post_detail_text.insert(1.0, details)

    def create_post(self):
        """Create a new post"""
        title = self.title_var.get().strip()
        content = self.content_text.get(1.0, tk.END).strip()
        status = self.status_var.get()

        if not title or not content:
            messagebox.showerror("Error", "Title and content are required.")
            return

        try:
            result = self.client.create_post(title, content, status)
            messagebox.showinfo("Success", "Post created successfully!")

            # Clear form
            self.title_var.set("")
            self.content_text.delete(1.0, tk.END)

            # Refresh posts if on posts tab
            self.load_posts()

        except Exception as e:
            messagebox.showerror("Error", f"Failed to create post: {e}")

    def full_sync(self):
        """Perform full synchronization"""
        try:
            sync_data = self.client.sync_posts()
            self.last_sync_timestamp = sync_data.get('timestamp')

            posts = sync_data.get('posts', [])
            sync_info = f"Full sync completed at {time.ctime()}\n"
            sync_info += f"Synced {len(posts)} posts\n"
            sync_info += f"Timestamp: {self.last_sync_timestamp}\n\n"

            self.sync_status_text.insert(tk.END, sync_info)
            self.sync_status_text.see(tk.END)

        except Exception as e:
            error_info = f"Full sync failed at {time.ctime()}: {e}\n\n"
            self.sync_status_text.insert(tk.END, error_info)
            self.sync_status_text.see(tk.END)

    def incremental_sync(self):
        """Perform incremental synchronization"""
        if not self.last_sync_timestamp:
            messagebox.showwarning("Warning", "No previous sync found. Please perform a full sync first.")
            return

        try:
            sync_data = self.client.sync_posts(self.last_sync_timestamp)
            self.last_sync_timestamp = sync_data.get('timestamp')

            posts = sync_data.get('posts', [])
            sync_info = f"Incremental sync completed at {time.ctime()}\n"
            sync_info += f"Found {len(posts)} changes\n"
            sync_info += f"New timestamp: {self.last_sync_timestamp}\n\n"

            self.sync_status_text.insert(tk.END, sync_info)
            self.sync_status_text.see(tk.END)

        except Exception as e:
            error_info = f"Incremental sync failed at {time.ctime()}: {e}\n\n"
            self.sync_status_text.insert(tk.END, error_info)
            self.sync_status_text.see(tk.END)

    def run(self):
        """Start the application"""
        self.root.mainloop()

if __name__ == "__main__":
    print("Laravel SSO Desktop Client")
    print("=" * 50)
    print("Make sure your Laravel server is running on http://localhost:8000")
    print("Update the Google Client ID in the code before running.")
    print()

    app = DesktopApp()
    app.run()
