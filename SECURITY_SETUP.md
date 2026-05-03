# Security Setup Guide

## API Key Configuration

This project uses the Groq API for AI features. To protect your API keys:

### 1. Server-Side Configuration (Recommended)

**For PHP files (CONTROLLER/cv_score.php):**

1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Add your Groq API key to `.env`:
   ```
   GROQ_API_KEY=your_actual_groq_api_key_here
   ```

3. The PHP code will automatically read from environment variables:
   ```php
   define('OPENAI_API_KEY', getenv('GROQ_API_KEY') ?: '');
   ```

### 2. Client-Side Configuration (Temporary Solution)

**For JavaScript files (VIEW/BackOffice/index.php, VIEW/FrontOffice/condidature.html):**

⚠️ **WARNING**: Storing API keys in client-side JavaScript is NOT secure. Anyone can view your API key in the browser.

**Recommended approach:**
1. Create a PHP endpoint that proxies requests to Groq API
2. Store the API key only on the server
3. Have JavaScript call your PHP endpoint instead of Groq directly

**Temporary approach (for development only):**
1. Replace `'YOUR_GROQ_API_KEY_HERE'` with your actual key
2. **NEVER commit this file to version control**
3. Add the file to `.gitignore` if it contains real keys

### 3. Get Your Groq API Key

1. Visit [Groq Console](https://console.groq.com/)
2. Sign up or log in
3. Navigate to API Keys section
4. Create a new API key
5. Copy the key (you won't be able to see it again)

### 4. Best Practices

✅ **DO:**
- Store API keys in environment variables
- Use server-side proxies for API calls
- Add `.env` to `.gitignore`
- Rotate API keys regularly
- Use different keys for development and production

❌ **DON'T:**
- Commit API keys to version control
- Store keys in client-side JavaScript
- Share keys in public repositories
- Use production keys in development

### 5. Recommended Architecture

```
Browser (JavaScript)
    ↓
Your PHP Backend (with API key)
    ↓
Groq API
```

This way, the API key never leaves your server.

## Additional Security Measures

1. **Rate Limiting**: Implement rate limiting on your API endpoints
2. **Authentication**: Require user authentication before allowing AI features
3. **Input Validation**: Always validate and sanitize user inputs
4. **HTTPS**: Use HTTPS in production to encrypt data in transit
5. **CORS**: Configure proper CORS headers to restrict API access

## Emergency Response

If you accidentally committed an API key:

1. **Immediately revoke the key** in Groq Console
2. Generate a new API key
3. Update your `.env` file with the new key
4. Remove the key from git history:
   ```bash
   git filter-branch --force --index-filter \
   "git rm --cached --ignore-unmatch path/to/file" \
   --prune-empty --tag-name-filter cat -- --all
   ```
5. Force push to remote (coordinate with team first)

## Support

For security concerns, contact your team lead or security officer.
