#Import Modules

progress_list = []

def progress_outcome(pass_credits, defer_credits, fail_credits):
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

    progress_list.append("\n")
    progress_list.append(result)
    progress_list.append("-")
    progress_list.append(pass_credits)
    progress_list.append(defer_credits)
    progress_list.append(fail_credits)
    
def printProgressList():
    print(*progress_list)


        

# I declare that my work contains no examples of misconduct, such as plagiarism, or collusion. 
# Any code taken from other sources is referenced within my code solution. 
# Student ID: w1870585
 
# Date: 19/11/2021
