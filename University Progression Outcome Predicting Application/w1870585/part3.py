#Initializing Variables

pass_credits = 0
defer_credits = 0
fail_credits = 0

progress = []
trailer = []
retriever = []
exclude = []

numbers = [0, 20, 40, 60, 80, 100, 120]

histogram_list = []

def progress_outcome(pass_credits, defer_credits,fail_credits):
    result = ""
    
    if pass_credits == 120:
        result = "Progress"
    elif pass_credits == 100:
        result = "Progress (module trailer)"
    elif pass_credits == 80:
        result = "Do not Progress - module retriever"
    elif pass_credits == 60:
        result = "Do not Progress - module retriever"
    elif pass_credits == 40 and defer_credits != 0:
        result = "Do not Progress - module retriever"
    elif pass_credits == 40 and defer_credits == 0:
        result = "Exclude"
    elif pass_credits == 20 and defer_credits >= 40:
        result = "Do not Progress - module retriever"
    elif pass_credits == 20 and defer_credits <= 20:
        result = "Exclude"
    elif pass_credits == 0 and defer_credits >= 60:
        result = "Do not Progress - module retriever"
    elif pass_credits == 0 and defer_credits <= 40:
        result = "Exclude"

    print(result)
    
    histogram_list.append("\n")
    histogram_list.append(result)
    histogram_list.append("-")
    histogram_list.append(pass_credits)
    histogram_list.append(defer_credits)
    histogram_list.append(fail_credits)
    


def histogram(pass_credits, defer_credits):
    if pass_credits == 120:
        progress.append(0)
    elif pass_credits == 100:
        trailer.append(0)
    elif pass_credits == 80:
        retriever.append(0)
    elif pass_credits == 60:
        retriever.append(0)
    elif pass_credits == 40 and defer_credits != 0:
        retriever.append(0)
    elif pass_credits == 40 and defer_credits == 0:
        exclude.append(0)
    elif pass_credits == 20 and defer_credits >= 40:
        retriever.append(0)
    elif pass_credits == 20 and defer_credits <= 20:
        exclude.append(0)
    elif pass_credits == 0 and defer_credits >= 60:
        retriever.append(0)
    elif pass_credits == 0 and defer_credits <= 40:
        exclude.append(0)
        
    
#To check values are in given range
        
def validation():
    global pass_credits, defer_credits, fail_credits
    while True:
        try:
            pass_credits = int(input("Please enter your credits at pass: "))#Calling validate_credits function to check user inputs at pass_credits are in correct range
        except ValueError:
            print("Integer required")#If user not input an integer print integer required
            continue
        else:
            if pass_credits not in numbers:
                print("Out of range")#If user inputs at pass_credits are not in correct range print "Out of range"
            elif pass_credits in numbers:
                break

    while True:
        try:
            defer_credits = int(input("Please enter your credits at defer: "))#If only above condition is true continue from this
        except ValueError:
            print("Integer required")#If user not input an integer print integer required
            continue
        else:
            if defer_credits not in numbers:#Calling validate_credits function to check user inputs at defer_credits are in correct range
                print("Out of range")
            elif defer_credits in numbers:
                break

    while True:
        try:
            fail_credits = int(input("Please enter your credits at fail: "))#If only above condition is true continue from this
        except ValueError:
            print("Integer required")#If user not input an integer print integer required
            continue
        else:
            if fail_credits not in numbers:#Calling validate_credits function to check user inputs at fail_credits are in correct range
                print("Out of range")#If user inputs at fail_credits are not in correct range print "Out of range"
            elif fail_credits in numbers:
                break

    while True:
        total_credits = pass_credits + defer_credits + fail_credits
        if total_credits != 120:#Checks all the user input values are in given range and summation is 120
            print("Total incorrect")#Assume that Out of range error prompt first and prints only one error form both of total and range errors
            validation()
            break
        else:
            progress_outcome(pass_credits, defer_credits,fail_credits)
            histogram(pass_credits, defer_credits)
            break #To exit from the loop and the program with a correct outcome when user inputs are acceptable

#Horizantal Histogram
        
def histogram_print():
    print("\n")
    print("-" * 60)
    print("Horizontal Histogram\n")
    print("Progress", len(progress), " :", "*" * len(progress))
    print("Trailer", len(trailer), "  :", "*" * len(trailer))
    print("Retriever", len(retriever), ":", "*" * len(retriever))
    print("Excluded", len(exclude), " :", "*" * len(exclude))
    total_outcomes = len(progress) + len(trailer) + len(retriever) + len(exclude)
    print("\n")
    print(total_outcomes, "outcomes in total.")
    print("-" * 60)


close = ""


def run_again():
    global close
    while True:
        print("\n")
        try:
            close = input("Would you like to enter another set of data?\n"
                          "Enter 'y' for yes or 'q' to quit and view results: ")
        except ValueError:
            print("Please Enter 'y' or 'q'")
        else:
            if close == "q":
                histogram_print()
                break

            elif close == "y":
                pass
                break

            else:
                print("Please Enter 'y' or 'q'")

    return close

def printHistogramList():
    print(*histogram_list)
    
    with open('listfile.txt', 'a') as filehandle:
        for listitem in histogram_list:
            filehandle.write('%s ' % listitem)

# I declare that my work contains no examples of misconduct, such as plagiarism, or collusion. 
# Any code taken from other sources is referenced within my code solution. 
# Student ID: w1870585
 
# Date: 28/11/2021

