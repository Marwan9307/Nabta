# Complexity and Testing Report

## 1. Quality Factors

> Selected pair: **Integrity** and **Efficiency**

### Integrity
- Integrity means the system keeps data correct, prevents unauthorized changes, and maintains consistency.
- Example in this project: report submission and retrieval should return accurate information and not lose or corrupt report state.

### Efficiency
- Efficiency is how fast and resource-light the code runs.
- Example in this project: a lightweight `ReportServices` implementation and fast report lookup help the system respond quickly.

### Non-independent pair example
- Integrity and efficiency are not completely independent.
- If the system adds extra integrity checks, it may slow execution and reduce efficiency unless the implementation is optimized.
- Therefore, improving integrity can impact efficiency, and a good design balances both.

---

## 2. LOC and Cyclomatic Complexity (CCM)

For the second requirement, I selected a single function and measured its lines of code and decision paths.

### Chosen function: `Router::dispatch($method, $uri)`
- LOC: 13 lines
- CCM: 4
- Reasoning: the function has one base path plus 3 decision points:
  1. `if (isset($this->routes[$method][$uri]))` for exact route matching
  2. `foreach` with pattern matching over parameterized routes
  3. `if (preg_match(...))` inside the loop for dynamic route matching
- Notes: this function contains the key control-flow structures used to calculate CCM.

### Additional example functions

#### ReportServices::submitReport($report_Type)
- LOC: 3 lines
- CCM: 1 (no decision points)
- Notes: simple state update and boolean return.

#### ReportServices::getReportType($report_Type_Enum)
- LOC: 2 lines
- CCM: 1
- Notes: direct string concatenation.

#### CalculateServices::calculateTrustScore($user_Registered_ID)
- LOC: 2 lines
- CCM: 1
- Notes: dummy calculation with random number.

#### Session::flash($key, $value = null)
- LOC: 6 lines
- CCM: 2 (one if branch)
- Notes: branch for storing vs retrieving flash data.

---

## 3. OO Complexity Metrics

### ReportServices
- Formula used:
  - WMC = sum of method complexities
  - DIT = depth of inheritance tree
  - NOC = number of direct subclasses
  - CBO = number of other classes coupled to this class
  - RFC = count of methods in class plus external methods called
  - LCOM = lack of cohesion measured by shared attribute usage

- WMC = 4 (4 methods, each CC = 1)
- DIT = 0 (no inheritance)
- NOC = 0 (no subclasses)
- CBO = 0 (methods do not depend on other classes)
- RFC = 4 (calls only its own methods / no external methods)
- LCOM = high (methods share no common instance data except `submitReport` uses `$reportsList`; the remaining methods do not use class attributes)

### CalculateServices
- WMC = 10 (10 methods, each CC = 1)
- DIT = 0
- NOC = 0
- CBO = 0
- RFC = 10
- LCOM = high / maximum by basic pair-count formula, because no methods share instance data (there are no class fields)

### Router
- WMC = 5 (methods `get`, `post` each 1; `dispatch` CC = 4; `call` CC = 1)
- DIT = 0
- NOC = 0
- CBO = 1 (dynamic controller dispatch indicates coupling to controller classes)
- RFC = 5 (4 public methods plus private `call`)
- LCOM = 1 (all methods operate on the same `$routes` attribute, so cohesion is reasonable)

---

## 4. Unit Testing Report (White-Box)

### Selected methods and path coverage

1. `ReportServices::submitReport()`
   - Path: add new report to internal list and return true.
   - Test case: pass a sample report key and assert the method returns `true`.

2. `ReportServices::getReportType()`
   - Path: format and return report type string.
   - Test case: pass `spam` and assert output equals `"Type: spam"`.

3. `ReportServices::getReportDetails()`
   - Path: return fixed details string.
   - Test case: pass `user` and assert exact string.

4. `ReportServices::getAnalyticalReports()`
   - Path: return fixed analytics string.
   - Test case: pass `monthly` and assert exact string.

5. `CalculateServices::calculateTrustScore()`
   - Path: random score generation path.
   - Test case: assert output is float between `1.0` and `5.0`.

6. `CalculateServices::calculateEcoPoints()`
   - Path: random range path.
   - Test case: assert output is between `100` and `1000`.

7. `CalculateServices::calculateCO2Equation()`
   - Path: fixed return path.
   - Test case: assert exact `12.5`.

8. `CalculateServices::calculateBundleDiscount()`
   - Path: fixed return path.
   - Test case: assert exact `10`.

9. `CalculateServices::calculateTotal()`
   - Path: fixed return path.
   - Test case: assert exact `150`.

10. `CalculateServices::calculateTotalPrice()`
    - Path: fixed return path.
    - Test case: assert exact `150`.

---

## 5. Functional / Black-Box Testing Report

### Boundary and equivalence ideas

- `calculateTrustScore()`
  - Boundary values: `1.0` and `5.0` are the extremes of the valid score range.
  - Test cases: call with any user id and assert result lies within the allowed range.

- `calculateEcoPoints()`
  - Boundary values: `100` and `1000`.
  - Test cases: call with valid transaction ids and assert the points are within range.

- `getReportType()`
  - Equivalence partitioning: valid strings, empty string, and numeric-like input.
  - Test cases: verify output format for each partition.

- `Session::flash()`
  - Boundary values: writing a value then reading it back, and reading a missing key.
  - Test cases: set flash data, then get it; also request a non-existing key and expect `null`.

- `Router::dispatch()`
  - Partitioning: exact route match, parameterized route match, and missing route.
  - Test cases: register both fixed and parameterized routes and verify that the correct handler is invoked or a 404 path is reached.

---

## 6. Changes made

- Fixed `tests/Unit/ReportServicesTest.php` path require to use `__DIR__`.
- Added `tests/Unit/CalculateServicesTest.php` with six function-level tests.

## 7. Validation

- The new tests cover at least six main functions in the system.
- The report class and calculation service are now both covered by explicit unit tests.
- The existing `ReportServicesTest` file is corrected and now includes an additional analytical report test.
