# Dynamic / Dependent Symfony Form Fields

[![CI](https://github.com/SymfonyCasts/dynamic-forms/actions/workflows/ci.yaml/badge.svg)](https://github.com/SymfonyCasts/dynamic-forms/actions/workflows/ci.yaml)

**NOTE**: This package is currently experimental. It seems to work great - but
forms are complex! If you find a bug, please open an issue!

Ever have a form field that depends on another?

You can find a [Demo with LiveComponent on Symfony UX](https://ux.symfony.com/demos/live-component/dependent-form-fields).

* Show a field only if another field is set to a specific value;
* Change the options of a field based on the value of another field;
* Have multiple-level dependencies (e.g. field A depends on field B
  which depends on field C).

```php
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder = new DynamicFormBuilder($builder);

    $builder->add('meal', ChoiceType::class, [
        'choices' => [
            'Breakfast' => 'breakfast',
            'Lunch' => 'lunch',
            'Dinner' => 'dinner',
        ],
    ]);

    $builder->addDependent('mainFood', ['meal'], function(DependentField $field, string $meal) {
        // dynamically add choices based on the meal!
        $choices = ['...'];

        $field->add(ChoiceType::class, [
            'placeholder' => null === $meal ? 'Select a meal first' : sprintf('What is for %s?', $meal->getReadable()),
            'choices' => $choices,
            'disabled' => null === $meal,
        ]);
    });
```

## Installation

Install the package with:

```bash
composer require symfonycasts/dynamic-forms
```

Done - you're ready to build dynamic forms!

## Usage

Setting up a dependent field is two parts:

1. [Usage in PHP](#usage-in-php) - set up your Symfony form to handle
   the dynamic fields;
2. [Updating the Frontend](#updating-the-frontend) - adding code to your
   frontend so that when one field changes, part of the form is re-rendered.

## Usage in PHP

Start by wrapping your `FormBuilderInterface` with a `DynamicFormBuilder`:

```php
use Symfonycasts\DynamicForms\DynamicFormBuilder;
// ...

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder = new DynamicFormBuilder($builder);

    // ...
}
```

`DynamicFormBuilder` has all the same methods as `FormBuilderInterface` plus
one extra: `addDependent()`. If a field depends on another, use this method
instead of `add()`

```php
// src/Form/FeedbackForm.php

// ...
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class FeedbackForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder =  new DynamicFormBuilder($builder);

        $builder->add('rating', ChoiceType::class, [
            'choices' => [
                'Select a rating' => null,
                'Great' => 5,
                'Good' => 4,
                'Okay' => 3,
                'Bad' => 2,
                'Terrible' => 1
            ],
        ]);

        $builder->addDependent('badRatingNotes', 'rating', function(DependentField $field, ?int $rating) {
            if (null === $rating || $rating >= 3) {
                return; // field not needed
            }

            $field->add(TextareaType::class, [
                'label' => 'What went wrong?',
                'attr' => ['rows' => 3],
                'help' => sprintf('Because you gave a %d rating, we\'d love to know what went wrong.', $rating),
            ]);
        });
    }
}
```

The `addDependent()` method takes 3 arguments:

1. The name of the field to add;
2. The name (or names) of the field that this field depends on;
3. A callback that will be called when the form is submitted. This callback
   receives a `DependentField` object as the first argument then the
   value of each dependent field as the next arguments.

Behind the scenes, this works by registering several form event listeners.
The callback be executed when the form is first created (using the initial
data) and then again when the form is submitted. This means that the callback
may be called multiple times.

Rendering the field is the same - just be sure to make sure the field exists
if it's conditionally added:

```twig
{{ form_start(form) }}
    {{ form_row(form.rating) }}

    {% if form.badRatingNotes is defined %}
        {{ form_row(form.badRatingNotes) }}
    {% endif %}

    <button>Send Feedback</button>
{{ form_end(form) }}
```

## Updating the Frontend

In the previous example, when the `rating` field changes, the form (or part of
the form) needs to be re-rendered so the `badRatingNotes` field can be added.

This library doesn't handle this for you, but here are the 2 main options:

### A) Use [Live Components](https://symfony.com/bundles/ux-live-component/current/index.html)

This is the easiest method: by rendering your form inside a live component,
it will automatically re-render when the form changes.

### B) Use [Symfony UX Turbo](https://symfony.com/bundles/ux-turbo/current/index.html#decomposing-complex-pages-with-turbo-frames)

If you are already using Symfony UX Turbo on your website, you can have a dynamic form running quickly without any JavaScript.

Or you may want to install Symfony UX Turbo, [check out the documentation](https://symfony.com/bundles/ux-turbo/current/index.html#installation).

> [!NOTE]
> You only need to have Turbo Frame, you can disable Turbo Drive if you do not use it, or do not want to use it.
> ie: `Turbo.session.drive = false;`

Simply add a `<turbo-frame>` around your form:

```twig
<turbo-frame id="rating-form">
    {{ form(form) }}
</turbo-frame>
```

From here you need two small changes:

First, in your form type:
 - You need to add an attribute on the choice field, so it auto-submits the form when changed (may need to be adapted to your own form if more complex)
 - Add a submit button, so in the controller you can differenciate from an auto-submit versus a user action


```diff
// src/Form/FeedbackForm.php

// ...

class FeedbackForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder =  new DynamicFormBuilder($builder);

        $builder->add('rating', ChoiceType::class, [
            'choices' => [
                'Select a rating' => null,
                'Great' => 5,
                'Good' => 4,
                'Okay' => 3,
                'Bad' => 2,
                'Terrible' => 1
            ],
+           // This will allow the form to auto-submit on value change
+           'attr' => ['onchange' => 'this.form.requestSubmit()'],
        ]);
+       // This will allow to differenciate between a user submition and an auto-submit
+       $builder->add('submit', SubmitType::class, [
+           'attr' => ['value' => 'submit'], // Needed for Turbo
+       ]);

        $builder->addDependent('badRatingNotes', 'rating', function(DependentField $field, ?int $rating) {
            if (null === $rating || $rating >= 3) {
                return; // field not needed
            }

            $field->add(TextareaType::class, [
                'label' => 'What went wrong?',
                'attr' => ['rows' => 3],
                'help' => sprintf('Because you gave a %d rating, we\'d love to know what went wrong.', $rating),
            ]);
        });
    }
}
```

Second, in your controller:
 - Specify the action on your form, [this is needed for Turbo Frame](https://symfony.com/bundles/ux-turbo/current/index.html#3-form-response-code-changes)
 - Handle the auto-submit by checking if the button has been clicked

```diff
// src/Controller/FeedbackController.php

    #[Route('/feedback', name: 'feedback')]
    public function feedback(Request $request): Response
    {
        //...

-       $feedbackForm = $this->createForm(FeedbackForm::class);
+       $feedbackForm = $this->createForm(FeedbackForm::class, options: [
+           // This is needed by Turbo Frame, it is not specific to Dependent Symfony Form Fields
+           'action' => $this->generateUrl('feedback'),
+       ]);
        $feedbackForm->handleRequest($request);
        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {

+           /** @var SubmitButton $submitButton */
+           $submitButton = $feedbackForm->get('submit');
+           if (!$submitButton->isClicked()) {
+               return $this->render('feedback.html.twig', ['feedbackForm' => $feedbackForm]);
+           }

            // Your code here
            // ...

            return $this->redirectToRoute('home');
        }

        return $this->render('feedback.html.twig', ['feedbackForm' => $feedbackForm]);
    }

```

### C) Write custom JavaScript

If you're not using Live Components, nor Turbo Frames, you'll need to write some custom
JavaScript to listen to the `change` event on the `rating` field and then
make an AJAX call to re-render the form. The AJAX call should submit the
form to its usual endpoint (or any endpoint that will submit the form), take
the HTML response, extract the parts that need to be re-rendered and then replace
the HTML on the page.

This is a non-trivial task and there may be room for improvement in this
library to make this easier. If you have ideas, please open an issue!
