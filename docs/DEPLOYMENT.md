# Deploying the live site (free hosting)

The brief requires a public URL. Both options below are free. Plan for about 15 minutes.

You need **`estatein.zip`** (the theme). Download it from this repository's **Releases** page, or click **Code → Download ZIP** and zip only the `estatein` folder.

---

## Option A – InfinityFree

1. Sign up at **infinityfree.com** → **Create Account** (hosting account) → pick a free subdomain, e.g. `estatein-yourname.rf.gd`.
2. In the client area open the account → **Control Panel** → **Softaculous Apps Installer** → **WordPress** → **Install**.
   - Protocol: `https://` (enable the free SSL certificate first under *SSL Certificates* if offered, otherwise use `http://`).
   - Set admin username, a strong password and your email. Install.
3. Log in at `https://your-subdomain/wp-admin`.
4. **Appearance → Themes → Add New → Upload Theme** → choose `estatein.zip` → **Install** → **Activate**.
5. **Plugins → Add New** → search **Secure Custom Fields** → **Install** → **Activate**.
6. **Appearance → Estatein Setup** → **Import demo content**. (Takes up to a minute on free hosting.)
7. **Settings → Permalinks** → confirm *Post name* → **Save** (refreshes the rewrite rules).
8. Open the site – done. Submit this URL to Growmodo.

> InfinityFree may take a few minutes for a new subdomain/SSL to become active. If the theme upload fails because of the upload size limit, upload the `estatein` folder to `htdocs/wp-content/themes/` with the File Manager or FTP instead.

## Option B – Wasmer Edge

1. Sign up at **wasmer.io** → **Deploy** → choose the **WordPress** template → create.
2. Open the generated URL, finish the WordPress setup, then follow steps 3–8 above.

---

## After deploying – quick checklist

- [ ] Home, About Us, Properties, a property page, Services and Contact Us all load.
- [ ] Mobile menu opens on a phone.
- [ ] Send a test message from the Contact page → it appears under **Inquiries** in the dashboard.
- [ ] **Theme Settings → Footer & Forms** → set the email that should receive form notifications.
- [ ] Optional: **Settings → General** → set Site Title "Estatein" and the tagline.

## Try it without hosting (WordPress Playground)

Open the Playground link in the main README. It boots a complete copy in your browser with the plugin installed and the demo imported. It resets when the tab closes, so it is for testing only – it does not replace the live URL the brief asks for.
