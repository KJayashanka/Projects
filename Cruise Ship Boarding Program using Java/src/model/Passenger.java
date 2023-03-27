package model;

import java.io.Serializable;
import java.util.Comparator;
/*
 * Passenger class (encapsulated class)
 */
public class Passenger implements Serializable,Comparable<Passenger>{


    private int passengerId;
    private String cabinNo;
    private String firstName;
    private String lastName;
    private String nic;
    private String address;

    private double expenses;

    public Passenger(String first_name,String last_name, double expenses) {
        this.firstName = first_name;
        this.lastName = last_name;
        this.setExpenses(expenses);
    }


    public Passenger() {}

    public int setNic(String nic) {
        char lastCharacter = nic.charAt(nic.length()-1);
        if(lastCharacter == 'V' || lastCharacter == 'v' && nic.length() == 10){
            this.nic = nic;
            return 0;
        }else{
            System.out.println("\tInvalid input, check again !");
            return 1;
        }
    }

    public String getNic() {
        return nic;
    }
    @Override
    //override method to compare Passenger names
    public int compareTo(Passenger Passenger) {
        Integer obj1 = this.passengerId;
        Integer obj2 = Passenger.passengerId;
        return obj1.compareTo(obj2) ;
    }

    @Override
    //this is required to print the user friendly information about the Passenger
    public String toString() {
        return "\n"+this.passengerId + "\t\t" + this.firstName + "\t" + this.cabinNo + "\t\t" +this.address + "\n";
    }

    public static Comparator<Passenger> NameComparator = new Comparator<>() {

        @Override
        public int compare(Passenger e1, Passenger e2) {
            return e1.getFirstName().compareTo(e2.getFirstName());
        }
    };

    public int getPassengerId() {
        return passengerId;
    }

    public void setPassengerId(int passengerId) {
        this.passengerId = passengerId;
    }

    public String getCabinNo() {
        return cabinNo;
    }

    public void setCabinNo(String cabinNo) {
        this.cabinNo = cabinNo;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }


    public double getExpenses() {
        return expenses;
    }


    public void setExpenses(double expenses) {
        this.expenses = expenses;
    }
}

