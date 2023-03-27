package main;

import java.io.IOException;
import java.util.Scanner;

import controller.Methods;
import view.View;
/*
 * main body of the program
 * runs using do-while loop
 */
public class Main{
    static String response;
    static Scanner sc = new Scanner(System.in);
    public static void main(String[] args) throws ClassNotFoundException, IOException {
        System.out.println("\tWelcome to Cruise Ship Boarding System");
        System.out.println("\t____________________________________________________\n");
        System.out.println("Choose your option from here..\n");

        Methods.writeCabinFile();
        //loop for control main body according to user response
        do{
            System.out.println("View all Cabins - V");
            System.out.println("Add a Passenger to a Cabin - A");
            System.out.println("Display Empty Cabins - E"); 
            System.out.println("Delete Customer from Cabin - D");
            System.out.println("Find Cabin from Passenger name - F");
            System.out.println("Store program data into file - S");
            System.out.println("Load program data from file - L");
            System.out.println("View passengers Ordered alphabetically by name - O");
            System.out.println("Print expenses per passenger - T");
            System.out.println("Exit - X");
            System.out.print("Enter your response - ");
            response = sc.next();

            //refer view class by creating view object by passing response
            new View(response,sc);
            System.out.println("\nDo you want to perform any task ?\n");

        }while(!response.equalsIgnoreCase("x"));
        sc.close();

        System.exit(0);
    }
    
//TODO - Calculate expenses of passengers
//TODO - Display expenses of passengers
//TODO - Add already saved passenger to a cabin
//TODO - Implement the queue

//TODO - CHECK ALL THE METHODS IMPLEMENTED SO FAR ************
}

