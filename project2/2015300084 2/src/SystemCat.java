
import java.io.BufferedWriter;
import java.io.File;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.util.Scanner;
import java.util.*;

public class SystemCat {
	Scanner scanConsole = new Scanner(System.in);
	
	//RandomAccessFile sysCat;
	
	public SystemCat() throws IOException{
		File out = new File("SysCat.txt");
		  RandomAccessFile sysCat =new RandomAccessFile (out, "rw"); //creating a systemcat txt
		  sysCat.seek(0);
		  File f=new File("RecordFiles");
		  File g=new File("DiscDirectory");
		  boolean firsttime = f.mkdir(); //creating record files directory
		  boolean secondtime=g.mkdir();
		  
		  if(firsttime==true){
		  sysCat.writeInt(0); //number of types
		  }
		  
		  /* switch(opr) {
			case 0:
				System.out.println("Good Bye..");
				return;
			case 1:
				System.out.println("Type a typeName");
				String typeName = scanconsole.next();
				createType();
				break;
			case 2:
				deleteType();
				break;
			case 3:
				listTypes();
				break;
			case 4:
				createRecord();
				break;
			case 5:
				deleteRecord();
				break;
			case 6:
				listRecords();
				break;
			case 7:
				searchRecord();
				break;
			} */
		  
	}
	void createType(String typeName,String[] arrOfSTR,BufferedWriter writer) throws IOException {
		while(isalreadyExists(typeName)|typeName.length()>10|typeName.length()==0){
			System.out.println("Enter an appropriate type name.");
			typeName=scanConsole.next();
		}  
			
			String typeName2=typeName;
			
			int numberofFields=Integer.parseInt(arrOfSTR[3]);
			String[] fieldNames=arrOfSTR;
			System.out.println("Enter the name of fields .");
			for(int i=0;i<numberofFields;i++){
				
				
				while(fieldNames[i+4].length()!=10){
					fieldNames[i+4]+="*";
				}
			}
			File out = new File("SysCat.txt");
			RandomAccessFile sysCat =new RandomAccessFile (out, "rw");
			sysCat.seek(0);	
			
			int typeCounter = sysCat.readInt();
			System.out.println(typeCounter);
			typeCounter++;
			sysCat.seek(0);
			sysCat.writeInt(typeCounter);				//increment the number of types

			sysCat.seek(sysCat.length());
			while(typeName.length()!=10){
				typeName+="*";
			}
			sysCat.writeUTF(typeName);  //typeName 12 byte for 10 characters long string
			
			sysCat.writeInt(numberofFields);// number of fields 4 byte 
			
			sysCat.writeBoolean(false);//set isDeleted to false
			

			for(int i = 0; i < numberofFields; i++) 
				sysCat.writeUTF(fieldNames[i+4]); 	

			for(int i = numberofFields; i<10; i++)
				sysCat.writeUTF("**********");		//complete the remaining fields area 

			
			System.out.println(typeName2+" has been created successfuly");
			File record=new File("RecordFiles/"+typeName2+0+".txt"); //create first file
			RandomAccessFile recordFile = new RandomAccessFile(record , "rw");  
			recordFile.writeInt(0); 			// set  number of Records to 0
			recordFile.writeInt(numberofFields);  // set number of fields

			for(int i=0; i<numberofFields; i++) {
				recordFile.writeUTF(fieldNames[i+4]);
			}
			for(int i=numberofFields; i<10; i++) {
				recordFile.writeUTF("**********");
			}
			
			recordFile.close();
			File disc=new File("DiscDirectory/"+typeName2+".txt");
			RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
			DiscFile.seek(0);
			DiscFile.writeInt(1);// set number of files to 1;
			DiscFile.writeInt(numberofFields);
			DiscFile.close();
				
	}
	
	
	
	void deleteType(String typeName) throws IOException{
		if(!isalreadyExists(typeName)) {				// check whether the type exists
			System.out.println("Type does not exist");
			return;
		}
		File out = new File("SysCat.txt");
		RandomAccessFile sysCat =new RandomAccessFile (out, "rw");
		sysCat.seek(0);

		int numberofTypes = sysCat.readInt();
		numberofTypes--;
		sysCat.seek(0);
		sysCat.writeInt(numberofTypes);
		for(int k=4; k<sysCat.length(); k+=137) {
			sysCat.seek(k);
			String poppedTypeName = sysCat.readUTF();
			int numOfRecords = sysCat.readInt();
			boolean isDeleted = sysCat.readBoolean();		//if the type is deleted, skip it
			if(isDeleted == true)
				continue;
			String temp = "";
			for(int i=0; i<10; i++) {
				if(poppedTypeName.charAt(i) != '*') {
					temp += poppedTypeName.charAt(i);
				}
			}
			if(temp.equals(typeName)) {
				sysCat.seek(sysCat.getFilePointer()-1);
				sysCat.writeBoolean(true);					//set isDeleted to true;
				System.out.println("girdi mi");
				break;
			}
		}
		File disc=new File("DiscDirectory/"+typeName+".txt");
		RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
		DiscFile.seek(0);
		int numberOfFiles=DiscFile.readInt();
		for(int i=0; i<numberOfFiles ; i++){
		
		File f=new File("RecordFiles/"+typeName+i+".txt");
		f.delete();
		}
		disc.delete();
		sysCat.close();
	}
	
	
	 void ListAllTypes(BufferedWriter writer) throws IOException {
		// TODO Auto-generated method stub
		File out = new File("SysCat.txt");
		RandomAccessFile sysCat =new RandomAccessFile (out, "rw");
		sysCat.seek(0);
		int numOfTypes=sysCat.readInt();
		 Vector<String> types = new Vector<>();
		for(int i=4;i<sysCat.length();i+=137){
			sysCat.seek(i);
			String typeName=sysCat.readUTF();
			String temp="";
			sysCat.seek(i+16);
			boolean isDeleted=sysCat.readBoolean();
			if(isDeleted==false){
				for(int j=0; j<typeName.length(); j++) {
					if(typeName.charAt(j)!='*')
						temp+=typeName.charAt(j);
					
				}
				types.add(temp);
				//writer.write(typeName);
				//System.out.println(typeName);
				//writer.write("\n");
			}
			
		}
		 Collections.sort(types);
		 for(int i=0; i<types.size(); i++){
			 writer.write(types.get(i));
			 writer.write("\n");
		 }
		 sysCat.close();
	}
	 
	 
	 
	 void createRecord(String typeName,String[] arrOfSTR) throws IOException{
		 File disc=new File("DiscDirectory/"+typeName+".txt");
			RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
		while(!isalreadyExists(typeName)|typeName.length()>10|typeName.length()==0){
			System.out.println("Please enter a valid type name.");
			typeName=scanConsole.next();
		}
		DiscFile.seek(0);
		int numberOfFiles=DiscFile.readInt();
		int recordID = Integer.parseInt(arrOfSTR[3]);
		for(int num=0; num<numberOfFiles; num++){
			 File f1 = new File("RecordFiles/" + typeName+num+ ".txt"); //find the file with associated type			
			RandomAccessFile recordFile = new RandomAccessFile(f1, "rw");
		
		recordFile.seek(0);
		int numberofRecords=recordFile.readInt();
		if(numberofRecords<70){
		
		int numberofFields=recordFile.readInt();
		//yeni eklenti
		int currentByte=1;
		int pageCount=1;
		for(int k= 128; k<recordFile.length(); k+=5+(numberofFields-1)*4){
			if(currentByte%10==1) {
				pageCount++;
				System.out.println("Reading page #"+ (pageCount)+" ...");
			}
			recordFile.seek(k);
			int currentID = recordFile.readInt();
			boolean isDeleted = recordFile.readBoolean();
			currentByte++;
			if(isDeleted==true){
				recordFile.seek(recordFile.getFilePointer()-5);
				recordFile.writeInt(recordID);
				recordFile.writeBoolean(false);
				String[] fieldNames=arrOfSTR;
				for(int i=0; i<numberofFields-1; i++) {
					System.out.println("Please enter a fieldname"+i);
					 int a= Integer.parseInt(fieldNames[i+4]);
					recordFile.writeInt(a);
				}
				numberofRecords++;
				recordFile.seek(0);
				recordFile.writeInt(numberofRecords);
				recordFile.close();
				return;
					
			}
		}
		// yeni eklenti sonu
		System.out.println("number of fields are"+numberofFields);
		recordFile.seek(recordFile.length());
		recordFile.writeInt(recordID); //record Id is set
		recordFile.writeBoolean(false); // isDeleted
		String[] fieldNames=arrOfSTR;
		for(int i=0; i<numberofFields-1; i++) {
			System.out.println("Please enter a fieldname"+i);
			 int a= Integer.parseInt(fieldNames[i+4]);
			recordFile.writeInt(a);
		}
		numberofRecords++;
		recordFile.seek(0);
		recordFile.writeInt(numberofRecords);
		recordFile.close();
		
	
		return;
		}
		recordFile.close();
		} // for loop finished no place for new record
		
		
		File f2 = new File("RecordFiles/" + typeName+numberOfFiles+ ".txt"); //find the file with associated type			
		RandomAccessFile recordFile = new RandomAccessFile(f2, "rw");
		DiscFile.seek(4);
		int numberofFields=DiscFile.readInt();
		recordFile.seek(0);
		recordFile.writeInt(1); 			// set  number of Records to 0
		recordFile.writeInt(numberofFields);  // set number of fields
		for(int i=0; i<10; i++) {
			recordFile.writeUTF("**********");
		}
		recordFile.seek(recordFile.length());
		recordFile.writeInt(recordID); //record Id is set
		recordFile.writeBoolean(false); // isDeleted
		String[] fieldNames=arrOfSTR;
		for(int i=0; i<numberofFields-1; i++) {
			System.out.println("Please enter a fieldname"+i);
			 int a= Integer.parseInt(fieldNames[i+4]);
			recordFile.writeInt(a);
		}
		numberOfFiles++;
		recordFile.close();
		DiscFile.seek(0);
		DiscFile.writeInt(numberOfFiles);
		DiscFile.close();
		
		 
		 
		 
		 
		 
	 }
	 void deleteRecord(String typeName,String id) throws IOException{
		 File disc=new File("DiscDirectory/"+typeName+".txt");
		RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
		while(!isalreadyExists(typeName)|typeName.length()>10|typeName.length()==0){
			System.out.println("Please enter a valid type name.");
			typeName=scanConsole.next();
		}
		DiscFile.seek(0);
		int numberOfFiles=DiscFile.readInt(); // get number of files
		for(int num=0; num<numberOfFiles ; num++){
			File f1 = new File("RecordFiles/" + typeName+num+ ".txt"); //find the file with associated type			
			RandomAccessFile recordFile = new RandomAccessFile(f1, "rw");
		
		int currentByte=1;
		int pageCount=0;
		int primaryKey = Integer.parseInt(id);
		recordFile.seek(4);
		int numberofFields=recordFile.readInt();
		for(int k= 128; k<recordFile.length(); k+=5+(numberofFields-1)*4){
			if(currentByte%10==1) {
				pageCount++;
				System.out.println("Reading page #"+ (pageCount)+" ...");
			}
			recordFile.seek(k);
			int currentID = recordFile.readInt();
			boolean isDeleted = recordFile.readBoolean();
			currentByte++;
			if(isDeleted==true)
				continue;
			if(currentID == primaryKey) {
				recordFile.seek(recordFile.getFilePointer()-1);
				recordFile.writeBoolean(true);
				recordFile.seek(0);
				int numberOfRecord=recordFile.readInt();
				numberOfRecord--;
				recordFile.seek(0);
				recordFile.writeInt(numberOfRecord);
				
				System.out.println("SUCCESS! The record deleted successfuly from the page #"+ pageCount);
				return;
				
			}
			
			
		}
		recordFile.close();
		
		}
	 }
	 
	 
	 
	 void listRecord(String typeName,BufferedWriter writer)  throws IOException{
		 File disc=new File("DiscDirectory/"+typeName+".txt");
			RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
			while(!isalreadyExists(typeName)|typeName.length()>10|typeName.length()==0){
				System.out.println("Please enter a valid type name.");
				typeName=scanConsole.next();
			}
			Vector<Integer> pKeys = new Vector<>();
			 HashMap<Integer, String> idfield = new HashMap<Integer, String>();
			 DiscFile.seek(0);
				int numberOfFiles=DiscFile.readInt(); // get number of files
			for(int num=0 ; num<numberOfFiles; num++){
				File f1 = new File("RecordFiles/" + typeName+num+ ".txt"); //find the file with associated type			
				RandomAccessFile recordFile = new RandomAccessFile(f1, "rw");
			int currentByte=1;
			int pageCount=0;
			 
			recordFile.seek(4);
			int numberofFields=recordFile.readInt();
			System.out.println("numberoffields"+numberofFields);
			for(int k= 128; k<recordFile.length(); k+=5+(numberofFields-1)*4){
				if(currentByte%10==1) {
					pageCount++;
					System.out.println("Reading page #"+ (pageCount)+" ...");
				}
				recordFile.seek(k);
				int currentID = recordFile.readInt();
				boolean isDeleted = recordFile.readBoolean();
				currentByte++;
				if(isDeleted==true)
					continue;
				System.out.println("\t"+currentByte+". recordID: "+currentID);
				String id=Integer.toString(currentID);
				//writer.write(id);
				pKeys.add(currentID);
				String temp="";
				for(int i=0; i<numberofFields-1; i++) {
					int current=recordFile.readInt();
					String currentField=Integer.toString(current);
					//writer.write(currentField);
					temp=temp+currentField+" ";
					
			}
				
				idfield.put(currentID, temp);
		 
				//writer.write("\n");
		 
	 }
			recordFile.close();
			}
			
			 Collections.sort(pKeys);
			 for(int i=0; i<pKeys.size(); i++){
				 String temp=pKeys.get(i).toString()+" ";
				 writer.write(temp);
				 writer.write(idfield.get(pKeys.get(i)));
				 writer.write("\n");
			 }
			
	 }
	 
	 
	 void searchRecord(String typeName,String id,BufferedWriter writer)  throws IOException{
		 if(!isalreadyExists(typeName)) {				// check whether the type exists
				System.out.println("Type does not exist");
				return ;
			}
		 File disc=new File("DiscDirectory/"+typeName+".txt");
		RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
		DiscFile.seek(0);
		int numberOfFiles=DiscFile.readInt(); // get number of files
		for(int num=0; num<numberOfFiles; num++){
			File f1 = new File("RecordFiles/" + typeName+num+ ".txt"); //find the file with associated type			
			RandomAccessFile recordFile = new RandomAccessFile(f1, "rw");
			int currentByte=1;
			int pageCount=0;
			System.out.println("enter a primary key");
			int primaryKey = Integer.parseInt(id);
			recordFile.seek(4);
			
			int numberofFields=recordFile.readInt();
			for(int k= 128; k<recordFile.length(); k+=5+(numberofFields-1)*4){
				if(currentByte%10==1) {
					pageCount++;
					System.out.println("Reading page #"+ (pageCount)+" ...");
				}
				recordFile.seek(k);
				int currentID = recordFile.readInt();
				boolean isDeleted = recordFile.readBoolean();
				currentByte++;
				if(isDeleted==true)
					continue;
				System.out.println("\t"+currentByte+". recordID: "+currentID);
				if(currentID == primaryKey){
					writer.write(id+" ");
				for(int i=0; i<numberofFields-1; i++) {
					int current = recordFile.readInt();
					String m=Integer.toString(current);
					writer.write(m+" ");
					
					
					
				
				
			}
				writer.write("\n");
				System.out.println("\nRecord has been found in the page #"+pageCount+"!");
				return;
		}
				
	 }
			recordFile.close();	
		}
		 
			
	 }
	 
	 
	 void updateRecord(String typeName,String id,String[] arrOfSTR) throws IOException{
		 if(!isalreadyExists(typeName)) {				// check whether the type exists
				System.out.println("Type does not exist");
				return ;
			}
		 File disc=new File("DiscDirectory/"+typeName+".txt");
			RandomAccessFile DiscFile = new RandomAccessFile(disc , "rw"); 
			DiscFile.seek(0);
			int numberOfFiles=DiscFile.readInt(); // get number of files
			for(int num=0; num<numberOfFiles; num++){
				File f1 = new File("RecordFiles/" + typeName+num+ ".txt"); //find the file with associated type			
				RandomAccessFile recordFile = new RandomAccessFile(f1, "rw");
			int currentByte=1;
			int pageCount=0;
			System.out.println("enter a primary key");
			int primaryKey = Integer.parseInt(id);
			recordFile.seek(4);
			
			int numberofFields=recordFile.readInt();
			for(int k= 128; k<recordFile.length(); k+=5+(numberofFields-1)*4){
				if(currentByte%10==1) {
					pageCount++;
					System.out.println("Reading page #"+ (pageCount)+" ...");
				}
				recordFile.seek(k);
				int currentID = recordFile.readInt();
				boolean isDeleted = recordFile.readBoolean();
				currentByte++;
				if(isDeleted==true)
					continue;
				
				if(currentID == primaryKey){
				for(int i=0; i<numberofFields-1; i++) {
					int current = recordFile.readInt();
					
					
					int newFName=Integer.parseInt(arrOfSTR[i+4]);
					
					recordFile.seek(recordFile.getFilePointer()-4);
					recordFile.writeInt(newFName);
					
				
				
			}
				System.out.println("\nRecord has been found in the page #"+pageCount+"!");
				return;
		}
		 
		 
			} 
			recordFile.close();
			}
		 
	 }
	 
	 
	 
	 
	public boolean isalreadyExists(String inp){
		File typeFiles=new File("RecordFiles/");
		String[] files=typeFiles.list();
		int i=0;
		while( i<files.length){
			if(files[i].equals(inp+0+".txt")){
				return true;
			}
			i++;
		} 
		return false; 
	}
		
		
	
	  
}
