# Google reCAPTCHA v3

## Summary
reCAPTCHA v3, the latest version from Google as of 11/23, can be used to lower the amount of spam submissions on forms. There are three parts to setting it up.

Part one is getting two api keys from the Goole reCAPTCHA admin console. The two keys are the `SITE KEY` and `SECRET KEY`.

Part two involves loading the reCAPTCHA `script` on the page with your form and calling its function after your form has been validated and is ready to be submitted.

The third part is on the backend. Your form will need a confirmation page or you'll need access to handle the form submission.

## How to Install
### Part 1 - Get the API Keys
Two scenarios can occur when getting the api keys: the website is already using reCAPTCHA v3 and the api keys exist or this will be the first reCAPTCHA on the site and they need to be created.

To check if the website is using reCAPTCHA v3 and the api keys exist, go to the [reCAPTCHA admin console](https://www.google.com/recaptcha/admin/create). Make sure you are signed into the proper Google account. The account could be basementsystems@gmail.com, treehouseinternetgroup@gmail.com, treehouseinternetgroup2@gmail.com, or treehousemarketing3@gmail.com.

Check if the site is in the sites dropdown at the top. It may be named the company name. The reCAPTCHA version being used for that site will be in the left of the dropdown.

![reCAPTCHA site dropdown](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-1.png)

#### API Keys Already Exist

If a v3 version of your site exists (the version will be in the dropdown next to the site), select your site.

If your site exists but it's not a v3 version, you will need to create new api keys (explained later).

After selecting your site, you should see a gear (settings) icon on the top right. Click the gear icon.

![reCAPTCHA gear icon](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-2.png)

Here are the settings where you will find the api keys. Click the reCAPTCHA keys dropdown and you'll see the `SITE KEY` and `SECRET KEY` (blurred for security). We will need these later so copy them somewhere or leave this tab open.

![reCAPTCHA secret keys](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-3.png)

#### Create New API Keys
If your site isn't in the sites dropdown or it doesn't have a v3 version, we will need to make one in the reCAPTCHA admin console.

From the admin console, click the + icon on the top right.

![reCAPTCHA plus icon](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-4.png)

You will need to enter a `Label` for the site (the name that will appear in the site dropdown in the reCAPTCHA admin) and the `Domain`. For clarity, name both the domain of the site. For example, `www.test.com`.

Make sure the `reCAPTCHA type` is set to `Score based (v3)` (should be default).

Hit the submit button at the bottom.

![reCAPTCHA new site](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-5.jpg)

The api keys `SITE KEY` and `SECRET KEY` will be on the next page. We will need these later so copy them somewhere or leave this tab open.

![reCAPTCHA api keys](https://raw.githubusercontent.com/th-frontend/components/main/widgets-modules/forms/reCAPTCHA-v3/img/recaptcha-6.jpg)

### Part 2 - Frontend Setup (form page)
On your form page, you will need to load the reCAPTCHA api `script`. Load this script at the top of your page (just before `</head>` in the advanced tab in the CMS). Replace `SITE_KEY` in the `src` with your `SITE KEY` from earlier:

`<script src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>`

When you plug in your real `SITE KEY` it might look something like this:

`<script src="https://www.google.com/recaptcha/api.js?render=6LctkPEoAAAAAPEY7yaSr3ExAliESbMGPTQV_9HY"></script>`

Next, we have to call a reCAPTCHA JavaScript function when your form is validated and ready to be submitted. Our form validator, `th_form_validator.js`, should be used for validating forms. This example assumes your form inputs are already setup properly to work with our validator. If not, you can learn how to set up your inputs [here](https://th-designbook.netlify.app/?path=/story/development-forms-form-validator--page).

Load the validator `script` in the `<head>` of the page.

`<script type="text/javascript" src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/th_form_validator.js?v=1"></script>`

Now, we need some JavaScript to:
1. Setup the validation
2. Call reCAPTCHA right before our form submits

The following snippet is an example of a basic validator for a contact form. It should be placed after somewhere after the form on the page.
```
<script>
  const form = document.querySelector("#contact_form");
  const validator = new ThFormValidator("contact_form", {
    disableSubmit: true,
  });

  form.addEventListener("submit", function (e) {
    // Don't submit form yet
    e.preventDefault();
    // Validate the form
    const errorFields = validator.validateAll();
    // If there are errors, don't submit the form and scroll to the form input with errors
    if (errorFields.length) {
      errorFields[0].focus();
      errorFields[0].scrollIntoView({ behavior: "smooth", block: "center" });
      return;
    }
    // If the form validates successfully, call google reCAPTCHA
    grecaptcha.ready(function () {
      grecaptcha
        .execute("SITE_KEY", {
          action: "submit",
        })
        .then(function (token) {
          // Create a hidden input for reCAPTCHA response / score
          let input = document.createElement("input");
          input.type = "hidden";
          input.name = "recaptcha_response";
          input.value = token;
          // Append the hidden input and submit the form
          form.appendChild(input);
          form.submit();
        });
    });
  });
</script>
```
In the code, you need to change `SITE_KEY` to your `SITE KEY` from earlier. You also need to change the `id` of the form being selected to the `id` of your form. In this example the `id` is `contact_form`.

This should be changed in two places:

`const form = document.querySelector("#contact_form");`

`const validator = new ThFormValidator("contact_form", { disableSubmit: true,});`

The frontend is now all set.

### Part 3 - Backend Setup (confirmation page)
The third and final part of setting up the reCAPTCHA is setting up some php on your confirmation page.

The following snippet is just a base to go off. If you already have a confirmation page, you may to need to adjust this snippet.

```
<?php

if (!empty($_POST)) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => 'SECRET_KEY',
        'response' => $_POST['recaptcha_response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]);

    $resp = json_decode(curl_exec($ch));
    curl_close($ch);

    if ($resp->success && $resp->score > 0.2) {
        // reCAPTCHA said this is good
        // do whatever you want (send form)
        $ouput = '<p>reCAPTCHA was successful!</p>'
    } else {
        // recaptcha said this is spam
        // output some kind of error / markup
        $output = '<p>reCAPTCHA error! You might be a robot!</p>';
    }
} else {
    // form is missing information / fields
    $output = '<p>Looks like you missed a field!</p>';
}
echo $output;
?>
```
In this snippet, you will need to swap `SECRET_KEY` with your `SECRET KEY`, the second api key from earlier. Remember, `SITE KEY` goes on frontend, `SECRET KEY` goes on backend.

This snippet does not submit the form, but the comments explain where to put code you want to run when reCAPTCHA fails, succeeds, or if the user somehow missed a field on the form.

The backend is all set and your reCAPTCHA should be working properly. Make sure you send a test lead through.
