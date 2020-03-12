<p align="center"><img src="https://raw.githubusercontent.com/millancore/lake/master/logo.jpg"></p>

<p align="center">
<a href="https://codeclimate.com/github/millancore/lake/maintainability"><img src="https://api.codeclimate.com/v1/badges/802c342410008cbd8c08/maintainability" /></a>
</p>

Lake is a command line utility that allows you to dynamically create classes and methods and create your reflexes for testing.


## Install 
```bash
composer require --dev millancore/lake
```

## Usage

lake works hand in hand with composer if you want it to automatically add the 'use' statements you must first run `lake dump`.

If there is more than one match, Lake will let you choose from those available on the project.


### Create a class + method

```bash
vendor/bin/lake make src/DirName/ClassName MethodName 
```

## Options

### Add parameters

```bash
vendor/bin/lake make src/DirName/ClassName MethodName -a ParameterType:varName
```
If the name of the variable is not defined Lake will create the variable name from the type.

```bash
vendor/bin/lake make src/DirName/ClassName show -a Request
```
Result
```php
public function show(Request $request) 
```

If the parameter accepts null

```bash
...ClassName MethodName -a ?Request
```
Default value 

```bash
...ClassName MethodName -a Int:id->123
```

### Return Type

```bash
vendor/bin/lake make src/DirName/ClassName show -a Request -r Response
```
```php
public function show(Request $request) : Response
```

### Visibility

By default the visibility is `public`, but we can define other types.

Option | Result
------ | -------
-v pub | `public` By default
-v pri | `private`
-v pro | `protected`

```bash
vendor/bin/lake make src/DirName/ClassName internal -a int:id -v pri
```

```php
private function internal(int $id) : array
```

