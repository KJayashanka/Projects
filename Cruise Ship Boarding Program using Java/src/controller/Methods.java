package controller;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.List;

import model.Passenger;
import model.Cabin;

public class Methods {
    //two main files which contains program info
    private static final File PassengerFile = new File("Passengers.txt");
    private static final File CabinFile = new File("Cabins.txt");

    public static void writeCabinFile(){
        List<Cabin> cabinList = Cabin.loadCabins();
        try {
            FileOutputStream fos = new FileOutputStream(CabinFile);
            ObjectOutputStream oos = new ObjectOutputStream(fos);   
            oos.writeObject(cabinList);
            oos.close(); 
        } catch(Exception ex) {
            ex.printStackTrace();
        }
    }

    //method to write data in array list of Passenger objects into file
    public static void addPassenger(ArrayList<Passenger> pssengerList) throws IOException {
        try {
            FileOutputStream writeData = new FileOutputStream(PassengerFile);
            ObjectOutputStream writeStream = new ObjectOutputStream(writeData);
            writeStream.writeObject(pssengerList);
            writeStream.flush();
            writeStream.close();
        } catch(Exception ex) {
            ex.printStackTrace();
        }
    }

    // method for check the entered Cabin will available
    public static boolean checkCabinAvailability(String cabin) throws IOException{
        ArrayList<Cabin> allCabinList = Methods.getAllCabins();

        for (Cabin c : allCabinList) {
            if(c.getCabinNo().equals(cabin))
                if(c.getAvailability().equals("Y"))
                    return true;
        }
        return false;
    }

    // method for update Cabin file while adding Passenger into Cabin
    public static boolean makeCabinNotAvailable(String cabinNo, Passenger passenger) throws IOException {
        ArrayList<Cabin> cabinList = getAllCabins();
        for (Cabin cabin : cabinList) {
            if(cabin.getCabinNo().equals(cabinNo) && cabin.getAvailability().equals("Y")){
                cabin.setAvailability("N");
                
                List<Passenger> cabinPassengersList = cabin.getCabinPassengersList();
                Passenger p = new Passenger(passenger.getFirstName(),passenger.getLastName(),cabin.getCost());
                if(cabinPassengersList == null){
                    cabinPassengersList = new ArrayList<>();
                    cabinPassengersList.add(p);
                }else if(cabinPassengersList.size() != 3){
                    cabinPassengersList.add(p);
                }

                cabin.setCabinPassengersList(cabinPassengersList);

                updateCabinFile(cabinList);
                return true;
            }
        }
        return false;
    }


    //method for update Cabin file while deleting Passenger into Cabin
    public static void makeCabinAvailable(Passenger passenger) throws IOException {
        ArrayList<Cabin> cabinList = getAllCabins();
        for (Cabin cabin : cabinList) {
            if(cabin.getCabinNo().equals(passenger.getCabinNo())){
                cabin.setAvailability("Y");
                
                List<Passenger> cabinPassengersList = cabin.getCabinPassengersList();
                if(cabinPassengersList != null){
                    for (Passenger p : cabinPassengersList) {
                        if(p.getFirstName().equalsIgnoreCase(passenger.getFirstName()) && p.getLastName().equalsIgnoreCase(p.getLastName())){
                            cabinPassengersList.remove(p);
                            break;
                        }
                        
                    }
                }

                cabin.setCabinPassengersList(cabinPassengersList);
            }
        }
        updateCabinFile(cabinList);
        
    }

    //method for search Passenger by his nic, name or Passenger ID
    public static Passenger searchPassenger(int passengerID) throws IOException {
        ArrayList<Passenger> passengerList = getAllPassengers();// array list for hold objects
        for (Passenger passenger : passengerList) {
            if(passenger.getPassengerId() == passengerID){
                return passenger;
            }
        }
        return null;
    }

    public static Cabin searchCabinByCustomerName(String name){
        ArrayList<Cabin> cabinList = Methods.getAllCabins();
        for (Cabin cabin : cabinList) {
            List<Passenger> cabinPassengersList = cabin.getCabinPassengersList();
            if(cabinPassengersList != null){
                for (Passenger passenger : cabinPassengersList) {
                    if(passenger.getFirstName().equalsIgnoreCase(name) || passenger.getLastName().equalsIgnoreCase(name))
                        return cabin;
                }

            }
        }
        return null;
    }

    // method for delete Passenger
    public static void deletePassenger(int passengerID) throws IOException {
        ArrayList<Passenger> passengerList = getAllPassengers();// array list for hold objects
        for (Passenger passenger : passengerList) {
            if(passenger.getPassengerId() == passengerID){
                passengerList.remove(passenger);
                break;

            }
        }
    }

    //search Cabin by its Cabin_No
    public static Cabin searchCabin(String Cabin_No) throws NumberFormatException, IOException {
        Cabin Cabin =null;
        if(CabinFile.exists()){
            BufferedReader bufferedReader=new BufferedReader(new FileReader(CabinFile));
            String readLine=null;
            while((readLine=bufferedReader.readLine())!=null){
                String data[]=readLine.split("#");
                if(data[0].equals(Cabin_No)){
                    Cabin=new Cabin(data[0],data[1], data[2], Double.parseDouble(data[3]),data[4]);
                    break;
                }
            }
        bufferedReader.close();
        }
        return Cabin;
    }

    // method for display all available Cabins
    public static ArrayList<Cabin> getAvailableCabins() throws NumberFormatException, IOException {
        ArrayList<Cabin> allCabinList = Methods.getAllCabins();

        ArrayList<Cabin> availableCabinList = new ArrayList<>();

        for (Cabin c : allCabinList) {
            if(c.getAvailability().equals("Y"))
                availableCabinList.add(c);
        }
        return availableCabinList;
    }

    public static void updateCabinFile(ArrayList<Cabin> cabinList){
        try {
            FileOutputStream fos = new FileOutputStream(CabinFile);
            ObjectOutputStream oos = new ObjectOutputStream(fos);   
            oos.writeObject(cabinList);
            oos.close(); 
        } catch(Exception ex) {
            ex.printStackTrace();
        }
    }

    // method for view all the Cabins
    @SuppressWarnings("unchecked")
    public static ArrayList<Cabin> getAllCabins() {
        List<Cabin> cabinList = new ArrayList<Cabin>();
        try{
            FileInputStream fis = new FileInputStream(CabinFile);
            ObjectInputStream ois = new ObjectInputStream(fis);
            cabinList = (ArrayList<Cabin>) ois.readObject();
            ois.close();
        }catch (IOException | ClassNotFoundException e){
            e.printStackTrace();
        }
  
        return (ArrayList<Cabin>) cabinList;
    }

    @SuppressWarnings("unchecked")
    public static ArrayList<Passenger> getAllPassengers() {
        List<Passenger> passengerList = new ArrayList<Passenger>();
        try{
            if(!PassengerFile.exists())
                return (ArrayList<Passenger>) passengerList;
            
                FileInputStream fis = new FileInputStream(PassengerFile);
            ObjectInputStream ois = new ObjectInputStream(fis);
            passengerList = (List<Passenger>) ois.readObject();
            ois.close();
            
        }catch (IOException | ClassNotFoundException e){
            e.printStackTrace();
        }
  
        return (ArrayList<Passenger>) passengerList;
    }

    //method for sorting Passenger type array
    public static void sortCabins(Passenger[] Passenger) throws NumberFormatException, IOException {
        Comparator<Passenger> NameComparator = new Comparator<Passenger>() { // using comparator to sort Passenger array

            @Override
            //override compare method
            public int compare(Passenger e1, Passenger e2) {
                return e1.getFirstName().compareTo(e2.getFirstName());
            }
        };
        //pass Passenger array to array class sort method to sort
        Arrays.sort(Passenger, NameComparator);
        System.out.println("Booked Cabins Ordered by Passenger name\n\nPassenger ID\tName\tCabin No.\tAddress\n"+Arrays.toString(Passenger));
        //display the sorted one
    }
}
