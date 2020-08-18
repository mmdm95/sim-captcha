# Simplicity Captcha
A library for captcha.

## Install
**composer**
```php 
composer require mmdm/sim-captcha
```

Or you can simply download zip file from github and extract it, 
then put file to your project library and use it like other libraries.

## How to use
```php
// to instance a captcha object
$captcha = CaptchaFactory::instance();

// now you have a captcha object then use it
// like this
$captcha_image = $captcha->generate();

// and put it where you want
// like inside a form

// the output will be like
<img src="BASE 64 ENCODED STRING">
```

## Available functions

There are two captcha generator for now.
- The traditional word captcha
- and a simple math captcha that provides [+, -, *]

You can get a new instance from specific class like this
```php
// traditional captcha
$captcha = new Captcha(ICaptchaLanguage $language);
// simple math captcha
$captcha = new CaptchaSimpleMath(ICaptchaLanguage $language);
```

The language specified above is to generate a captcha with your 
locale language words and numbers.

If you want to add a language for your locale you can implement 
`ICaptchaLanguage` interface. It has three functions you should 
implement.

```php
/**
 * Return numbers of language
 *
 * Note: If there is not any number on that language,
 * return empty array
 *
 * @return array
 */
public function numbers(): array;

/**
 * Return small alpha of language
 *
 * Note: If there is not any small alpha on that language,
 * return empty array
 *
 * @return array
 */
public function alphaSmall(): array;

/**
 * Return capital alpha of language
 *
 * Note: If there is not any capital alpha on that language,
 * return empty array
 *
 * @return array
 */
public function alphaCaps(): array;
```

For convenience there is a `CaptchaFactory` class that creates 
captcha you want. Just call `instance` method statically.

```php
$captcha = CaptchaFactory::instance();
``` 

If you need to type hint your variable, according to your IDE 
you should type hint variable. Must of editors should word with 
`@var`.

```php
/**
 * @var Captcha $captcha
 */
$captcha = CaptchaFactory::instance();

// now you can access to Captcha class, methods
$captcha->...;
```

`instance(int $type = CaptchaFactory::CAPTCHA, ICaptchaLanguage $language = null)`

As you can see there is a type variable that you can specify which 
class you need to instantiate. Under `CaptchaFactory` constants 
there are `CaptchaFactory::CAPTCHA` and `CaptchaFactory::CAPTCHA_SIMPLE_MATH`
for class instantiating.

Second parameter is language of captcha. If you don't specify that, 
it'll be English by default.

Note: Please use constants, because if numbers changed in future, 
you will have problem and have to refactoring captcha 
instantiating.

```php
$captcha = CaptchaFactory::instance(CaptchaFactory::CAPTCHA, new Persian());
$captcha->setFont(CaptchaFactory::FONT_IRAN_SANS);
```

Note: For now there are three languages:
[English, Persian, Arabic].

*** **Important Note** ***
Generate captcha codes in any languages are from **Left** 
to **Right**. Please inform user from this behavior if you 
have rtl language alphabet.

#### Common Methods

`generate()`

This method will generate captcha

```php
$generated_captcha = $captcha->generate();
```

`verify($input)`

This method is to verify generated captcha by sending user's value.

```php
$is_ok = $captcha->verify($user_input);
```

`setName(string $name = null)`

With this method you can store captcha under a name you specify. For 
example if you want to have captcha in several pages and user want 
to use multiple pages that have captcha, those generated captcha will 
mixed up and just one page can submit and others will say captcha is 
not valid! To solve this problem you should specify a name for 
generated captcha to prevent that.

Default name is captcha

```php
$captcha->setName('contact_us');
```

`getName()`

This method returns captcha name.

```php
$name = $captcha->getName();
```

`setExpiration(int $expire_time)`

You can specify expiration of captcha with this method

Default expiration is 600s

Note: pass time as seconds

```php
// set expiration to 2min or 120s
$captcha->setExpiration(120);
```

`getExpiration()`

This method returns expiration time of captcha in seconds

```php
$exp_time = $captcha->getExpiration();
```

#### Common Methods in Text Captcha

It has all methods of `Common Methods` and below methods.

`generateBase64Code(string $code = null)`

This method returns base64 image code only.

```php
$base64_code = $captcha->generateBase64Code();
// or
$base64_code = $captcha->generateBase64Code('mmdm95');
```

`setWidth(int $width)`

This method sets the captcha image width

Default is *200*.

```php
$captcha->setWidth(300);
```

`getWidth(): int`

This method returns captcha image width.

```php
$width = $captcha->getWidth();
```

`setHeight(int $height)`

This method sets the captcha image height

Default is *50*.

```php
$captcha->setHeight(70);
```

`getHeight(): int`

This method returns captcha image height.

```php
$height = $captcha->getHeight();
```

`setFont(string $filename)`

You can specify the font of captcha. Just send font's filename as 
parameter. By default there are three fonts with this library: 

- English -> Menlo-Regular,
- Persian -> IRANSansWeb,
- Arabic -> Lateef-Regular,

These fonts' filename have constant under `CaptchaFactory` class:
`CaptchaFactory::FONT_MENLO`, 
`CaptchaFactory::FONT_LATEEF` and 
`CaptchaFactory::FONT_IRAN_SANS`

or if you have other fonts to use, just send filename as parameter.

```php
$captcha->setFont($path_to_your_font_filename);
```

`getFont()`

This method returns font's filename string

```php
$font_filename = $captcha->getFont();
```

`setFontSize(int $size)`

You can specify size of captcha's font.

Default is *20*.

```php
$captcha->setFontSize(25);
```

`getFontSize(): int`

This method returns captcha's font size

```php
$font_size = $captcha->getFontSize();
```

`setImgAttributes(array $attributes)`

Because of generate method result, that is an image(for captcha 
text), setting attributes is a problem. With this method you can 
set attribute of captcha image.

```php
$captcha->setImgAttributes([
    'style' => 'display: block',
    'class' => 'img-rounded',
    'id' => 'captchaImage',
    'alt' => 'captcha image',
    ...
]);
```

`getImgAttributes(): array`

This method returns captcha image attributes as array

```php
$attributes = $captcha->getImgAttributes();

// output will be something like this
[
    'style' => 'display: block',
    'class' => 'img-rounded',
    'id' => 'captchaImage',
    'alt' => 'captcha image',
    ...
]
```

`getImgAttributesString(): string`

This method returns captcha image attributes as string

```php
$attributes_string = $captcha->getImgAttributesString();

// output will be something like this
style="display: block" class="img-rounded" id="captchaImage" alt="captcha image" ...
```

`addNoise(bool $answer)`

If you need to have noise on captcha, send true as parameter to 
this method.

Default is *true*

```php
$captcha->addNoise(false);
```

`useEnglishNumbersToVerify(bool $answer)`

If your user enter the captcha characters as input, it may be 
valid but not valid! It is because the user enter numbers in 
another language than locale. Like arabic numbers that user enter 
them as english, they may be equal but different in the same time. 
You can prevent that with sending *true* as parameter to this method 
and it'll convert all numbers from specified language to english and 
then verify entered code.

Default is *false*

Note: Use this method before verify method.
Note: Be careful and specify language before verify method to 
prevent unwanted behaviors.

```php
$captcha->useEnglishNumbersToVerify(true);
```

#### Captcha

It has all methods of `Common Methods in Text Captcha` and 
below methods.

`generate(string $code = null)`

This method generates a captcha code. Also you can set your own 
code to generate as captcha.

```php
$captcha_image = $captcha->generate();
// or 
$captcha_image = $captcha->generate('mmdm95');
```

`setLength(int $length): Captcha`

With this method you can set length of generated captcha

Default is *6*.

```php
$captcha->setLength(8);
```

`getLength(): int`

Get length of captcha characters that used in code generation

```php
$length = $caotcha->getLength();
```

`setDifficulty(int $difficulty): Captcha`

There is three difficulty for captcha codes [easy, medium, hard] 
that can pass to this method using constants under `CaptchaFactory`:
1. CaptchaFactory::DIFFICULTY_EASY
2. CaptchaFactory::DIFFICULTY_NORMAL
3. CaptchaFactory::DIFFICULTY_HARD

Easy makes captcha code to be just numbers.

Normal makes captcha code to be numbers and capital characters.

Hard makes captcha code to be numbers, capital characters and small 
characters.

Default is `CaptchaFactory::DIFFICULTY_NORMAL`

Note: All numbers and alphabet used from specified language
Note: Because `CaptchaFactory::DIFFICULTY_NORMAL` use capital 
characters, it is CASE-INSENSITIVE.

```php
$captcha->setDifficulty(CaptchaFactory::DIFFICULTY_HARD);
```

`getDifficulty(): int`

Get captcha difficulty

```php
$difficulty = $captcha->getDifficulty();
```

#### CaptchaSimpleMath

It has all methods of `Common Methods in Text Captcha` and 
below methods.

`setNumbersCount(int $count): CaptchaSimpleMath`

You can specify how many numbers should participate in. It 
should be a number between 2 and 5(inclusive).

Default is *2*.

```php
$captcha->setNumbersCount(3);
```

`getNumbersCount(): int`

This method returns numbers of participated numbers in captcha.

```php
$count = $captcha->getNumbersCount();
```

`useMultiplyOperands(): CaptchaSimpleMath`

By default the operands for math are [+, -] and if you need 
multiplication, just call this method before captcha generation.

```php
$captcha->useMultiplyOperands();
```

# How to generate captcha multiple times in a page

Because of page caching, you can not generate captcha with 
the captcha object in one request, therefore you should use ajax.

In tests folder you can see an example of this usage. The captcha 
will generate by ajax from beginning.

**index.php** is the base page for it and **captcha.php** is the 
page that gives captcha each time.

See **script** part of **index.php** if you are not familiar with 
ajax creation or not using a library like **jQuery** to create 
ajax request.

Note: If you store captcha under a specific name, be careful and 
send name of captcha with ajax request.

# License
Under MIT license.