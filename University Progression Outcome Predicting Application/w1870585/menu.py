#Import modules

import part1
import part2
import part3
import part4

menu_open = ""
close = ""

def menu():
    global menu_open, close
    while True:
        print("\n")
        try:
            menu_open = input("Enter '1' to Open Main Version(Progress Outcome): \n"
                              "Enter '2' to Open Main Version (Validation): \n"
                              "Enter '3' to Horizantal Histogram with List: \n"
                              "Enter '4' to Open Vertical Histogram with Text file: \n"
                              "Enter 'q' to Quit: ")
        except ValueError:
            print("Please Enter '1' '2' '3' '4' '5' or 'q'")
        else:
            if menu_open == "q":
                print("'q' Pressed, Quit Programme")
                quit()
            elif menu_open == "1":
                print("-" * 60)
                print("Main Version(Progress Outcome)\n")
                pass_credits = int(input("Please enter your credits at pass: "))
                defer_credits = int(input("Please enter your credits at defer: "))
                fail_credits = int(input("Please enter your credits at fail: "))
                
                part1.progress_outcome(pass_credits, defer_credits,fail_credits)
                
                print("-" * 60)
            elif menu_open == "2":
                print("-" * 60)
                print("Main Version (Validation)\n")
                
                part2.validation()
               
                
                print("-" * 60)
            elif menu_open == "3":
                print("-" * 60)
                print("Staff Version with Histogram\n")

                while True:
                    part3.validation()
                    close = part3.run_again()

                    if close == 'q':
                        break

                print("-" * 60)

                part3.printHistogramList()

            elif menu_open == "4":
                print("-" * 60)
                print("Vertical Histogram\n")

                while True:
                    part4.validation()
                    close = part4.run_again()

                    if close == 'q':
                        break

                part4.printVHistogramList()



menu()



