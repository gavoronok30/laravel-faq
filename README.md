## 
## Description

FAQ backend module.

- categories
- FAQs
- linking FAQs and categories to different models via traits

## Install

Open file **bootstrap/providers.php** and connect the provider from the package (optional, using laravel discovered package system by default)

```
\Crow\Faq\Providers\FaqProvider::class,
```

## Run commands

For creating config file

```
php artisan vendor:publish --provider="Crow\Faq\Providers\FaqProvider" --tag=config
```

For creating language file (if need for setting description or custom exception text)

```
php artisan vendor:publish --provider="Crow\Faq\Providers\FaqProvider" --tag=lang
```

For creating migration file

```
php artisan faq:publish --tag=migration
```

For generate table

```
php artisan migrate
```

## Command

Re sort all category or faq

```
php artisan faq:resort category
php artisan faq:resort faq
```

## Usage

Eloquent models for use. Used to retrieve or save FAQ and categories

```
\Crow\Faq\Models\FaqCategory
\Crow\Faq\Models\Faq
```

The user model will be used as an example `$user`, but you can connect it to any other model.
You can use helper `\Crow\Faq\Helpers\FaqHelper` or service `\Crow\Faq\Services\FaqService`.

### Category

Synchronizing categories with an entity (models or ID)

```
\Crow\Faq\Helpers\FaqHelper::syncModelAndCategories($user, [$faqCategory1, $faqCategory2]);
\Crow\Faq\Helpers\FaqHelper::syncModelAndCategories($user, [1, 5]);
```

Add 1 category (model or ID)

```
\Crow\Faq\Helpers\FaqHelper::attachModelAndCategory($user, $faqCategory1);
\Crow\Faq\Helpers\FaqHelper::attachModelAndCategory($user, 10);
```

Remove 1 category (model or ID)

```
\Crow\Faq\Helpers\FaqHelper::detachModelAndCategory($user, $faqCategory1);
\Crow\Faq\Helpers\FaqHelper::detachModelAndCategory($user, 10);
```

Sorting all categories

```
\Crow\Faq\Helpers\FaqHelper::resortCategories();
```

Get the next rank for a new category

```
\Crow\Faq\Helpers\FaqHelper::rankNextForCategory();
```

### Faq

Synchronizing FAQs with an entity (models or ID)

```
\Crow\Faq\Helpers\FaqHelper::syncModelAndFaqs($user, [$faq1, $faq2]);
\Crow\Faq\Helpers\FaqHelper::syncModelAndFaqs($user, [1, 5]);
```

Add 1 FAQ (model or ID)

```
\Crow\Faq\Helpers\FaqHelper::attachModelAndFaq($user, $faqCategory1);
\Crow\Faq\Helpers\FaqHelper::attachModelAndFaq($user, 10);
```

Remove 1 FAQ (model or ID)

```
\Crow\Faq\Helpers\FaqHelper::detachModelAndFaq($user, $faqCategory1);
\Crow\Faq\Helpers\FaqHelper::detachModelAndFaq($user, 10);
```

Sorting all FAQs (optional: ID Categories)

```
\Crow\Faq\Helpers\FaqHelper::resortFaqs();
\Crow\Faq\Helpers\FaqHelper::resortFaqs([1,5]);
```

Get the next rank for a new FAQ (optional: ID Category)

```
\Crow\Faq\Helpers\FaqHelper::rankNextForFaq();
\Crow\Faq\Helpers\FaqHelper::rankNextForFaq(1);
```

### Using other models together with FAQ

To connect, you need to use traits

For categories

```
class User
{
    use \Crow\Faq\Traits\FaqCategoryRelationTrait;
    ...
```

For FAQs

```
class User
{
    use \Crow\Faq\Traits\FaqRelationTrait;
    ...
```

After synchronizing categories or FAQ with the model, you can then use methods to get the linked models of the FAQ module.

```
$user->faqCategories; // Collection, categories
$user->faqCategoriesActive; // Collection, only active categories
$user->faqs; // Collection, FAQs
$user->faqsActive; // Collection, only active FAQs
```
