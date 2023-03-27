package model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/*
 * Cabin class(encapsulated class)
 */
public class Cabin implements Serializable{

    private String cabinNo;
    private String cabinType;
    private String floor;
    private double cost;
    private String availability;

    private List<Passenger> cabinPassengersList;

    public Cabin(String cabinNo, String cabinType, String floor, double cost,String availability) {
        this.cabinNo = cabinNo;
        this.cabinType = cabinType;
        this.floor = floor;
        this.cost = cost;
        this.availability = availability;
    }

    public static List<Cabin> loadCabins(){
        List<Cabin> cabinList = new ArrayList<>();
        Cabin cabin = null;
        cabin = new Cabin("1", "Inside Stateroom", "1", 100000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("2", "Ocean View Stateroom", "2", 400000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("3", "Inside Stateroom", "1", 300000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("4", "Mini-Suite      ", "3", 1000000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("5", "Inside Stateroom", "3", 200000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("6", "Ocean View Stateroom", "1", 500000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("7", "Verandah Stateroom", "2", 800000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("8", "Inside Stateroom", "1", 190000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("9", "Inside Stateroom", "2", 300000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("10", "Verandah Stateroom", "1", 150000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("11", "Suite           ", "3", 2000000.00, "Y");
        cabinList.add(cabin);
        cabin = new Cabin("12", "Balcony Cabin   ", "3", 300000.00, "Y");
        cabinList.add(cabin);
        
        return cabinList;
    }

    public String getCabinNo() {
        return cabinNo;
    }

    public void setCabinNo(String cabinNo) {
        this.cabinNo = cabinNo;
    }

    public String getCabinType() {
        return cabinType;
    }

    public void setCabinType(String cabinType) {
        this.cabinType = cabinType;
    }

    public String getFloor() {
        return floor;
    }

    public void setFloor(String floor) {
        this.floor = floor;
    }

    public double getCost() {
        return cost;
    }

    public void setCost(double cost) {
        this.cost = cost;
    }

    public String getAvailability() {
        return availability;
    }

    public void setAvailability(String availability) {
        this.availability = availability;
    }

    public List<Passenger> getCabinPassengersList() {
        return cabinPassengersList;
    }

    public boolean setCabinPassengersList(List<Passenger> cabinPassengersList) {
        if(cabinPassengersList != null && cabinPassengersList.size() < 4)
            this.cabinPassengersList = cabinPassengersList;
        else
           return false;

        return true;
    }
}
