package view;

import controller.Methods;
import controller.PassengerQueue;
import model.Cabin;
import model.Passenger;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Scanner;

public class View {
    String res;
    static PassengerQueue PassengerQueue = new PassengerQueue(7);// Create Passenger queue
    //constructor with String argument which refers main class user response
    public View(String res, Scanner sc) throws ClassNotFoundException, IOException{
        this.res = res;
        if(res.equalsIgnoreCase("A")){
            addPassenger(sc);
        }else if(res.equalsIgnoreCase("D")){
            deletePassenger(sc);
        }else if(res.equalsIgnoreCase("F")){
            findFromName(sc);
        }else if(res.equalsIgnoreCase("E")){
            displayEmptyCabins();
        }else if(res.equalsIgnoreCase("V")){
            viewAllCabins();
        }else if(res.equalsIgnoreCase("O")){
            viewPassengersByOrder();
        }else if(res.equalsIgnoreCase("T")){
            viewPassengersExpenses();
        }else if(res.equalsIgnoreCase("X")){
            System.exit(0);
        }
    }

    private void viewPassengersExpenses() {
        try {
            ArrayList<Passenger> passengerList = Methods.getAllPassengers();//array list for holding Passenger objects
            ArrayList<Cabin> allCabinList = Methods.getAllCabins(); //array list which holds Cabin objects
            
            for (Passenger p : passengerList) {
                for (Cabin c : allCabinList) {
                    if(p.getCabinNo().equals(c.getCabinNo())){
                        System.out.println("\nPassenger Name\t\tCabin No.\tCabin type\t\tCost($)\n");
                        System.out.println(p.getFirstName()+"\t\t"+c.getCabinNo()+"\t"+c.getCabinType()+"\t\t"+String.format("%.2f", c.getCost())+"\n");
            
                    }
                }
            }
        
        }catch (NumberFormatException e) { // exception handling
            System.out.println("\tProblem with loading data, Try again !");
            e.printStackTrace();
        }
    }

    //method for view Cabins ordered alphabetically by Passenger name
    private void viewPassengersByOrder() {
        try {
            ArrayList<Passenger> passengerList = Methods.getAllPassengers();//array list for holding Passenger objects
            
            String[] passengers = new String[passengerList.size()];
            for (int i = 0; i < passengers.length; i++) {
                passengers[i] = passengerList.get(i).getFirstName();
            }
            
            for(int a = 0; a < passengers.length - 1; a++){
                for(int b = a + 1; b < passengers.length; b++)
                {
                    if(passengers[a].compareTo(passengers[b]) > 0)
                    {
                    String temp = passengers[a];
                    passengers[a] = passengers[b];
                    passengers[b] = temp;
                    }
                }
            }  
            
            System.out.println("\nPassenger Names - Alphabetical Order");
            System.out.println(Arrays.toString(passengers));

        } catch (NumberFormatException e) { // exception handling
            System.out.println("\tProblem with loading data, Try again !");
            e.printStackTrace();
        }

    }

    // method for view all Cabins
    private void viewAllCabins() throws ClassNotFoundException, IOException {
        try {
            ArrayList<Cabin> allCabinList = Methods.getAllCabins(); //array list which holds Cabin objects

            System.out.println("\n\nCruise Ship - All Cabins");
            // for print a table using Cabin information
            System.out.println("\nCabin No.\t\tCabin type\t\tFloor\t\tCost($)\t\tAvailability\n");
            for(Cabin Cabin : allCabinList){
                System.out.println(Cabin.getCabinNo()+"\t\t"+Cabin.getCabinType()+"\t\t"+Cabin.getFloor()+"\t\t"+String.format("%.2f", Cabin.getCost())+"\t\t"+Cabin.getAvailability());
            }
        } catch (NumberFormatException e) {// exception handling
            System.out.println("\tError with Cabins file, Check again !");
            e.printStackTrace();
        }

    }

    //method for display all available Cabins
    private static void displayEmptyCabins() {
        try {
            ArrayList<Cabin> emptyCabinList = Methods.getAvailableCabins();// array list for holding Cabin objects

            //to print a table
            System.out.println("\n\nCruise Ship - Empty Cabins");
            System.out.println("\nCabin No.\tCabin type\tFloor\t\tCost($)\t\tAvailability\n");
            for(Cabin cabin : emptyCabinList){
                System.out.println(cabin.getCabinNo()+"\t\t"+cabin.getCabinType()+"\t"+cabin.getFloor()+"\t"+cabin.getCost()+"\t\t"+cabin.getAvailability());
            }
        } catch (NumberFormatException | IOException e) {
            System.out.println("\tError with Cabins file, Check again !");
            e.printStackTrace();
        }

    }

    // method for search Cabin by Passenger name
    private void findFromName(Scanner sc) {
        System.out.print("\nEnter Passenger Name: ");
        String name = sc.next();

        Cabin cabin = Methods.searchCabinByCustomerName(name); 
        
        if(cabin != null){
            System.out.println("\nCabin No.\tCabin type\tFloor\t\tCost($)\t\tAvailability\n");
            System.out.println(cabin.getCabinNo()+"\t\t"+cabin.getCabinType()+"\t"+cabin.getFloor()+"\t"+String.format("%.2f", cabin.getCost())+"\t\t"+cabin.getAvailability());
        }else{   
            System.out.println("\nSorry, No cabin under this customer name.");
        }
            
    }

    //register new Passenger
    public static void addPassenger(Scanner sc){
        Passenger passenger = new Passenger(); // create new Passenger object
        try {
            ArrayList<Passenger> passengerList = Methods.getAllPassengers();// create array list for holding rather Passenger objects

            //get the user input and set it to Passenger object

            System.out.print("\nEnter first name : ");
            String first_name = sc.next();
            passenger.setFirstName(first_name);

            System.out.print("Enter last name : ");
            String last_name = sc.next();
            passenger.setLastName(last_name);

            //to validate nic
            int res=0 ;
            do{
                System.out.print("Enter NIC : ");
                String nic = sc.next();
                res = passenger.setNic(nic);// validate it while set the nic
            }while(res!=0);

            System.out.print("Enter address : ");
            String address = sc.next();
            passenger.setAddress(address);

            //give user to input number of Cabins he/she need and then run loop for input the Cabin numbers
            System.out.println("\t - Available Cabins - ");
            displayEmptyCabins();

            System.out.print("Enter Cabin No. : ");
            String cabin = sc.next();
            passenger.setCabinNo(cabin);

            if(passengerList != null && passengerList.size() > 0)
                passenger.setPassengerId(passengerList.get(passengerList.size()-1).getPassengerId()+ 1);
            else
                passenger.setPassengerId(1);

            passengerList.add(passenger);// add object to array list

            boolean isCabin = Methods.makeCabinNotAvailable(cabin, passenger);//update Cabin file

            if(!isCabin){
                PassengerQueue.add(passenger);// addPassenger to the queue
                System.out.println("\n\tPassenger record added to the queue successfully !");

                System.out.print("Do you want to display first three Passengers in the Queue? (Y / N)  ");
                String s = sc.next();

                if(s.equalsIgnoreCase("y")){// display Passenger records of the queue
                    PassengerQueue.displayPassenger();	//calling method from queue class
                }else if(s.equalsIgnoreCase("n")){
                    System.out.println("Passenger details will be in the queue.");
                }
            }else{
                Methods.addPassenger(passengerList);//write array into file
                System.out.println("\n\tPassenger record added successfully !");
            }
        } catch (IOException e) {
            System.out.println("\tError with Passenger file, Check it !");// error handling
            e.printStackTrace();
        }
    }

    //method for delete Passenger from Cabin
    public static void deletePassenger(Scanner sc){
        try {
            //get Passenger id to find Passenger
            System.out.print("\nEnter Passenger ID : ");
            int passengerId = sc.nextInt();

            Passenger passenger = Methods.searchPassenger(passengerId);// create Passenger object to old data
            if(passenger !=  null){
                // display Passenger info
                System.out.println("\nPassenger Name : "+passenger.getFirstName()+" "+passenger.getLastName());
                System.out.println("Cabin No. : "+passenger.getCabinNo());
                System.out.println("NIC : "+passenger.getNic());
                System.out.println("Address : "+passenger.getAddress());

                //get user response to make sure to delete
                System.out.print("\n\tDo you want remove this record ??(Yes or No) ");
                String res = sc.next();
                if(res.equalsIgnoreCase("yes")){
                    Methods.deletePassenger(passengerId);// delete Passenger
                    Passenger p = PassengerQueue.contains(passenger);// check deleting Passenger is in the queue
                    if(p != null){
                        ArrayList<Passenger> passengerList = Methods.getAllPassengers();
                        passengerList.remove(passenger);

                        passengerList.add(p);
                        Methods.addPassenger(passengerList);

                        PassengerQueue.removePassenger(p);// calling remove method for remove Passenger from queue
                    }else{
                        Methods.makeCabinAvailable(passenger);
                    }
                    System.out.println("\n\tPassenger record deleted successfully !");
                }else{
                    System.out.print("\n\tTerminate Option");
                }
            }else{
                System.out.println("\tInvalid Passenger ID, try again !");
            }
        } catch (IOException e) {
            System.out.println("\tError with Passenger file, Check it !");// exception handling
            e.printStackTrace();
        } 
    }
}


