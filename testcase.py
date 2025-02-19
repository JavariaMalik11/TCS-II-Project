from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import unittest

class OutfitStoreTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        # Set up Chrome options for headless mode
        chrome_options = Options()
        chrome_options.add_argument("--headless")

        # Specify the path to your ChromeDriver executable
        chrome_driver_path = "C:\\chromedriver\\chromedriver-win32\\chromedriver.exe"

        # Initialize WebDriver instance and store it as a class attribute
        cls.driver = webdriver.Chrome(service=Service(chrome_driver_path), options=chrome_options)

        # Note: Ensure that the URL includes the protocol (http/https)
        cls.base_url = "http://localhost/TCS-II%20Project"

    @classmethod
    def tearDownClass(cls):
        # Close the browser after all tests are done
        cls.driver.quit()

    def setUp(self):
        # Additional setup tasks for each test case (if any)
        pass

    def wait_for_element(self, by, value):
        return WebDriverWait(self.driver, 10).until(EC.presence_of_element_located((by, value)))

    def test_login_successful(self):
        print("Testing successful login...")
        self.driver.get(self.base_url + "/login.php")
        print("Current URL:", self.driver.current_url)  # Print current URL for debugging
        username_input = self.wait_for_element(By.NAME, "username")
        password_input = self.wait_for_element(By.NAME, "password")
        submit_button = self.wait_for_element(By.CSS_SELECTOR, "input[type='submit']")

        username_input.send_keys("admin")  # Replace with a valid username
        password_input.send_keys("admin")   # Replace with a valid password
        submit_button.click()

        self.assertEqual(self.driver.current_url, self.base_url + "/dashboard.php")

    def test_login_failed(self):
        print("Testing failed login...")
        self.driver.get(self.base_url + "/login.php")
        print("Current URL:", self.driver.current_url)
        username_input = self.wait_for_element(By.NAME, "username")
        password_input = self.wait_for_element(By.NAME, "password")
        submit_button = self.wait_for_element(By.CSS_SELECTOR, "input[type='submit']")

        username_input.send_keys("invalid_username")
        password_input.send_keys("invalid_password")
        submit_button.click()

        error_message = self.wait_for_element(By.CSS_SELECTOR, "p[style='color: red;']").text
        print("Error message displayed:", error_message)  # Debugging output
        self.assertEqual(error_message, "Invalid username or password. Please try again.")

    def test_empty_login_fields(self):
        print("Testing empty login fields...")
        self.driver.get(self.base_url + "/login.php")
        print("Current URL:", self.driver.current_url)
        submit_button = self.wait_for_element(By.CSS_SELECTOR, "input[type='submit']")
        submit_button.click()

        username_input = self.wait_for_element(By.NAME, "username")
        password_input = self.wait_for_element(By.NAME, "password")

        self.assertTrue(username_input.get_attribute("required"))
        self.assertTrue(password_input.get_attribute("required"))

    def test_successful_add_to_cart(self):
        print("Testing adding to cart...")
        self.driver.get(self.base_url + "/dashboard.php")
        print("Current URL:", self.driver.current_url)
        product_name_input = self.wait_for_element(By.NAME, "product_name")
        quantity_input = self.wait_for_element(By.NAME, "quantity")
        add_to_cart_button = self.wait_for_element(By.CSS_SELECTOR, "input[value='Add to Cart']")

        product_name_input.send_keys("Bag")  # Replace with an actual product name
        quantity_input.send_keys("2")  # Replace with the desired quantity
        add_to_cart_button.click()

        success_message = self.wait_for_element(By.CSS_SELECTOR, "p[style='color:lightgreen']").text
        print("Success message displayed:", success_message)  # Debugging output
        self.assertEqual(success_message, "Product added to your order.")

    def test_remove_from_cart(self):
        print("Testing removing from cart...")
        self.driver.get(self.base_url + "/dashboard.php")
        print("Current URL:", self.driver.current_url)
        remove_button = self.wait_for_element(By.CSS_SELECTOR, "input[value='Remove']")
        remove_button.click()

        success_message = self.wait_for_element(By.CSS_SELECTOR, "p[style='color: lightcoral;']").text
        print("Success message displayed:", success_message)  # Debugging output
        self.assertEqual(success_message, "Product removed from your order.")

if __name__ == "__main__":
    unittest.main()
