package controller;

import model.Passenger;
/*
 * queue interface
 * implemented queue class
 * had override methods
 */
interface Queue{
    //public boolean add(int element);
    public Passenger removeDisplayed();
    public int size();
    public Passenger remove();
    public void clear();
    public void printQueue();
    public boolean empty();
    public boolean full();
    public int indexOf(Passenger Passenger);
    public Passenger contains(Passenger Passenger);
    public void displayPassenger();
}
public class PassengerQueue implements Queue{
    private Passenger[] queue;// Passenger array
    private int pointer=0; //Current location of the array
    public PassengerQueue(int capacity){
        queue=new Passenger[capacity];//get the que capacity
    }
    //check whether the queue is empty or not
    public boolean empty(){
        return pointer==0;
    }
    // check whether the queue is full or not
    public boolean full(){
        return pointer==queue.length;
    }
    //to clear the queue
    public void clear(){
        pointer=0;
    }
    //to get the size of the queue
    public int size(){
        return pointer;
    }
    //to add Passenger object to queue
    public boolean add(Passenger element){
        if(full()){
            System.out.println(queue[0]);
            remove();
        }
        queue[pointer++]=element;
        return true;

    }
    //to print queue
    public void printQueue(){
        System.out.println ("Passenger ID\tName\tRoom No.\tAddress\n");
        for(int i=0;i<pointer;i++){
            System.out.print(queue[i]);
        }
    }
    //to get index of the queue element
    public int indexOf(Passenger Passenger){
        int index=-1;
        for(int i=0;i<pointer;i++){
            if(queue[i].equals(Passenger)){
                index=i;
                break;
            }
        }
        return index;
    }
    //to check whether the expected object is in the queue
    public Passenger contains(Passenger passenger){
        Passenger p = null;
        for(int i = 0; i<pointer; i++){
            if(queue[i].getCabinNo().equals(passenger.getCabinNo())){
                p = queue[i];
                break;
            }
        }
        return p;
    }
    //to remove displayed Passengers
    public Passenger removeDisplayed(){
        if(empty()){
            System.out.println("Queue is empty");
            return null;
        }else if(pointer>=2){
            for(int i=3;i<pointer;i--){
             //   Passenger temp=queue[i];
                queue[i]=queue[i+1];
            }
            pointer = pointer-3;
            return null;
        }else{
            System.out.println("There is/ are only "+pointer+" Passengers in the queue, can't be remove");
        }
        return null;
    }
    //to display Passengers
    public void displayPassenger() {
        if(pointer>=3){
            System.out.println ("Passenger ID\tName\tCabin No.\tAddress\n");
            for (int i = 0; i < 3 ; i++) {
                System.out.println(queue[i]);
            }
            removeDisplayed();
        }else{
            System.out.println("Only "+pointer+" Passenger/s here..\n");
            System.out.println ("Passenger ID\tName\tCabin No.\tAddress\n");
            for (int i = 0; i < pointer; i++) {
                System.out.println(queue[i]);
            }
            clear();
        }
    }
    //to remove Passenger when Passenger asked
    public Passenger remove() {
        if(empty()){
            System.out.println("Queue is empty");
            return null;
        }else{
            Passenger temp=queue[0];
            for(int i=0;i<pointer;i++){
                queue[i]=queue[i+1];
            }
            pointer--;
            return temp;
        }
    }
    //to remove Passenger when queue is full
    public void removePassenger(Passenger Passenger) {
        for(int i=0;i<pointer;i++){
            if(queue[i].getPassengerId() == Passenger.getPassengerId()){
                for (int j = 0; j < pointer; j++) {
                    queue[i] = queue[i+1];
                }
            }
        }
    }
}

