# si-sso-lcbo
Authenticating using SSO and utilizing LCBO API

Note: The below usage assumes you have Docker and GIT installed, you created a Twitter app, and have registered for an LCBO API key

Usage:

1) Checkout the si-sso-lcbo repo: git clone https://github.com/robertdamiano/si-sso-lcbo.git

2) Change directory to si-sso-lcbo: cd si-sso-lcbo

3) Build the Docker image: docker build -t robertdamiano/si-sso-lcbo .

4) Run the docker image: docker run -d -p 0.0.0.0:80:80 --env CONSUMER_KEY=<twitter_api_key> --env CONSUMER_SECRET=[twitter_api_secret] --env OAUTH_CALLBACK=[twitter_api_callback_url] --env LCBO_API_KEY=[lcbo_api_key] robertdamiano/si-sso-lcbo. The OAUTH_CALLBACK can be set to http://127.0.0.1/, but make sure your Twitter app callback URL matches

5) Go to http://127.0.0.1/
